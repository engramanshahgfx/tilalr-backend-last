<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

/**
 * OTP Service - Centralized OTP generation and verification
 * 
 * Supports three modes configured via config/otp.php (OTP_MODE env variable):
 * - 'fixed': Uses a fixed OTP code (OTP_FIXED_CODE) for development/testing
 * - 'random': Generates random 6-digit codes (logged but not sent via SMS)
 * - 'sms': Full SMS integration (requires SMS provider configuration)
 * 
 * This design makes it easy to switch to real SMS when Saudi SMS provider is integrated.
 */
class OtpService
{
    protected $smsService;
    protected $mode;
    protected $fixedCode;
    protected $expiryMinutes;
    protected $resendCooldown;
    protected $maxAttempts;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
        $this->mode = config('otp.mode', 'fixed');
        $this->fixedCode = config('otp.fixed_code', '1234');
        $this->expiryMinutes = (int) config('otp.expiry_minutes', 5);
        $this->resendCooldown = (int) config('otp.resend_cooldown', 60);
        $this->maxAttempts = (int) config('otp.max_attempts', 3);
    }


    /**
     * Generate OTP code based on configured mode
     *
     * @return string The plain text OTP code
     */
    public function generateCode(): string
    {
        if ($this->mode === 'fixed') {
            return (string) $this->fixedCode;
        }

        // Generate random 6-digit code for 'random' and 'sms' modes
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP to a phone number
     *
     * @param string $phone Phone number
     * @param string $type OTP type ('register' or 'login')
     * @return array ['success' => bool, 'message' => string, 'code' => string (for testing)]
     */
    public function send(string $phone, string $type = 'login'): array
    {
        // Check resend cooldown (60 seconds)
        if (!$this->canResend($phone, $type)) {
            return [
                'success' => false,
                'message' => 'Please wait before requesting a new code.',
                'cooldown' => true,
            ];
        }

        $plain = $this->generateCode();
        $hashed = Hash::make($plain);
        $expiresAt = Carbon::now()->addMinutes($this->expiryMinutes);

        // Store OTP in database (with cache fallback)
        $this->storeOtp($phone, $hashed, $type, $expiresAt);

        // Send SMS based on mode and check provider result
        $smsSent = $this->sendSms($phone, $plain, $type);

        if ($this->mode === 'sms' && !$smsSent) {
            Log::error('OTP SMS failed to send via provider', [
                'phone' => $phone,
                'type' => $type,
            ]);

            return [
                'success' => false,
                'message' => __('auth.failed_send_otp'),
            ];
        }

        Log::info("OTP [{$this->mode} mode] sent to {$phone} for {$type}", [
            'phone' => $phone,
            'type' => $type,
            'mode' => $this->mode,
            'code' => $plain, // Log code for all development modes
            'sms_sent' => $smsSent,
        ]);

        return [
            'success' => true,
            'message' => 'OTP sent successfully.',
            'code' => in_array($this->mode, ['fixed', 'random']) ? $plain : null, // Return code in dev modes
        ];
    }

    /**
     * Verify OTP code
     *
     * @param string $phone Phone number
     * @param string $code OTP code to verify
     * @param string $type OTP type ('register' or 'login')
     * @return array ['success' => bool, 'message' => string, 'user' => User|null, 'token' => string|null]
     */
    public function verify(string $phone, string $code, string $type = 'login'): array
    {
        // In fixed mode, simply compare against the fixed code
        if ($this->mode === 'fixed') {
            if ($code !== (string) $this->fixedCode) {
                return [
                    'success' => false,
                    'message' => 'Invalid verification code.',
                ];
            }

            return $this->handleVerificationSuccess($phone, $type);
        }

        // For random/sms modes, check against stored OTP
        // First try cache
        $cacheKey = "otp:{$type}:{$phone}";
        $cached = Cache::get($cacheKey);

        if ($cached && Hash::check($code, $cached)) {
            Cache::forget($cacheKey);
            return $this->handleVerificationSuccess($phone, $type);
        }

        // Try database
        try {
            $otp = OtpCode::where('phone', $phone)
                ->where('type', $type)
                ->whereNull('used_at')
                ->orderByDesc('created_at')
                ->first();

            if (!$otp) {
                return [
                    'success' => false,
                    'message' => 'No code found or already used.',
                ];
            }

            if ($otp->isExpired()) {
                return [
                    'success' => false,
                    'message' => 'Code has expired. Please request a new one.',
                ];
            }

            if ($otp->attempts >= $this->maxAttempts) {
                return [
                    'success' => false,
                    'message' => 'Maximum attempts exceeded. Please request a new code.',
                ];
            }

            if (!Hash::check($code, $otp->code)) {
                $otp->attempts = $otp->attempts + 1;
                $otp->save();
                return [
                    'success' => false,
                    'message' => 'Invalid verification code.',
                    'attempts_remaining' => $this->maxAttempts - $otp->attempts,
                ];
            }

            // Mark as used
            $otp->markUsed();

            return $this->handleVerificationSuccess($phone, $type);

        } catch (\Throwable $e) {
            Log::error('OTP verification DB error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Verification failed. Please try again.',
            ];
        }
    }

    /**
     * Verify the code itself without trying to load user - useful for reset flows
     * Returns ['success' => bool, 'message' => string]
     */
    public function verifyCode(string $phone, string $code, string $type = 'login'): array
    {
        // Fixed mode: validate against fixed code
        if ($this->mode === 'fixed') {
            if ($code !== (string) $this->fixedCode) {
                return ['success' => false, 'message' => 'Invalid verification code.'];
            }
            return ['success' => true, 'message' => 'Code verified (fixed mode)'];
        }

        // Check cache first
        $cacheKey = "otp:{$type}:{$phone}";
        $cached = Cache::get($cacheKey);
        if ($cached && Hash::check($code, $cached)) {
            Cache::forget($cacheKey);
            return ['success' => true, 'message' => 'Code verified (cache)'];
        }

        // Database verification
        try {
            $otp = OtpCode::where('phone', $phone)
                ->where('type', $type)
                ->whereNull('used_at')
                ->orderByDesc('created_at')
                ->first();

            if (!$otp) return ['success' => false, 'message' => 'No code found or already used.'];
            if ($otp->isExpired()) return ['success' => false, 'message' => 'Code has expired. Please request a new one.'];
            if ($otp->attempts >= $this->maxAttempts) return ['success' => false, 'message' => 'Maximum attempts exceeded. Please request a new code.'];

            if (!Hash::check($code, $otp->code)) {
                $otp->attempts = $otp->attempts + 1;
                $otp->save();
                return ['success' => false, 'message' => 'Invalid verification code.', 'attempts_remaining' => $this->maxAttempts - $otp->attempts];
            }

            // Mark as used
            $otp->markUsed();

            return ['success' => true, 'message' => 'Code verified'];

        } catch (\Throwable $e) {
            Log::error('OTP verification DB error', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Verification failed. Please try again.'];
        }
    }

    /**
     * Find a user by phone using several normalized candidate forms
     */
    public function findUserByPhone(string $phone)
    {
        $candidates = [];
        $raw = trim($phone);
        $digits = preg_replace('/\D/', '', $raw);

        $candidates[] = $raw;
        if ($digits) {
            $candidates[] = $digits;

            // If starts with 0, try replacing leading 0 with +966 and without 0
            if (preg_match('/^0+/', $digits)) {
                $noZero = preg_replace('/^0+/', '', $digits);
                $candidates[] = '+966' . $noZero;
                $candidates[] = '966' . $noZero;
                $candidates[] = $noZero;
            } else {
                // If Saudi local (9 digits, starting with 5), try adding +966
                if (preg_match('/^[5][0-9]{8}$/', $digits)) {
                    $candidates[] = '+966' . $digits;
                    $candidates[] = '966' . $digits;
                }
            }

            // Also try with leading + if missing
            if (!preg_match('/^\+/', $raw) && preg_match('/^9+/', $digits)) {
                $candidates[] = '+' . $digits;
            }
        }

        // Unique and not empty
        $candidates = array_values(array_unique(array_filter($candidates)));

        // Debug log: show attempted candidates
        Log::info('OTP phone lookup candidates', ['original' => $phone, 'candidates' => $candidates]);

        foreach ($candidates as $c) {
            try {
                $user = User::where('phone', $c)->first();
                if ($user) {
                    Log::info('OTP phone lookup matched user', ['candidate' => $c, 'user_id' => $user->id]);
                    return $user;
                }
            } catch (\Throwable $e) {
                Log::warning('User phone lookup failed', ['phone' => $c, 'error' => $e->getMessage()]);
            }
        }

        Log::info('OTP phone lookup no match', ['original' => $phone, 'candidates' => $candidates]);
        return null;
    }

    /**
     * Handle successful verification - find/activate user and generate token
     */
    protected function handleVerificationSuccess(string $phone, string $type): array
    {
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found.',
            ];
        }

        // For registration, mark phone as verified (if column exists)
        if ($type === 'register') {
            try {
                if (Schema::hasColumn('users', 'phone_verified_at') && !$user->phone_verified_at) {
                    $user->phone_verified_at = Carbon::now();
                    $user->save();
                }
            } catch (\Throwable $e) {
                // Column might not exist yet - log but continue
                Log::warning('Could not set phone_verified_at', ['error' => $e->getMessage()]);
            }
        }

        // Generate authentication token
        $token = null;
        try {
            $token = $user->createToken('otp-auth')->plainTextToken;
        } catch (\Throwable $e) {
            // Token creation failed (e.g., DB problems) - log and continue so we can still return useful info
            Log::error('Token creation failed', ['error' => $e->getMessage()]);
            $token = null;
        }

        // Attempt to load roles and permissions but don't fail if it errors (DB schema may be incomplete in dev)
        try {
            $user->load('roles.permissions');
            $permissions = $user->roles->flatMap(fn($role) => $role->permissions->pluck('name'))->unique()->values();
        } catch (\Throwable $e) {
            Log::warning('Failed to load roles/permissions after OTP verification', ['error' => $e->getMessage()]);
            $permissions = collect();
        }

        // If token creation failed, return success but inform the caller
        if (!$token) {
            return [
                'success' => true,
                'message' => 'Verified but token creation failed.',
                'user' => $user,
                'roles' => $user->roles ? $user->roles->pluck('name') : collect(),
                'permissions' => $permissions,
            ];
        }

        return [
            'success' => true,
            'message' => $type === 'register' ? 'Phone verified successfully.' : 'Login successful.',
            'user' => $user,
            'roles' => $user->roles ? $user->roles->pluck('name') : collect(),
            'permissions' => $permissions,
            'token' => $token,
        ];
    }

    /**
     * Check if user can request a new OTP (cooldown period)
     */
    protected function canResend(string $phone, string $type): bool
    {
        // In fixed (development) mode allow immediate resend to facilitate testing
        if ($this->mode === 'fixed') {
            return true;
        }

        try {
            $recent = OtpCode::where('phone', $phone)
                ->where('type', $type)
                ->orderByDesc('created_at')
                ->first();

            if ($recent && $recent->created_at->gt(Carbon::now()->subSeconds($this->resendCooldown))) {
                return false;
            }
        } catch (\Throwable $e) {
            // If DB is unavailable, check cache
            $cacheKey = "otp_cooldown:{$type}:{$phone}";
            if (Cache::has($cacheKey)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Store OTP in database with cache fallback
     */
    protected function storeOtp(string $phone, string $hashedCode, string $type, Carbon $expiresAt): void
    {
        // Always set cache as fallback
        $cacheKey = "otp:{$type}:{$phone}";
        Cache::put($cacheKey, $hashedCode, now()->addMinutes($this->expiryMinutes));

        // Set cooldown cache
        Cache::put("otp_cooldown:{$type}:{$phone}", true, now()->addSeconds($this->resendCooldown));

        // Try to store in database
        try {
            OtpCode::create([
                'phone' => $phone,
                'code' => $hashedCode,
                'type' => $type,
                'expires_at' => $expiresAt,
            ]);
        } catch (\Throwable $e) {
            Log::warning('OTP DB write failed, using cache only', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Send SMS based on configured mode
     */
    /**
     * Send SMS based on configured mode
     * Returns true when message was queued/sent (or logged in dev), false on failure.
     */
    protected function sendSms(string $phone, string $code, string $type): bool
    {
        $appUrl = config('app.url', 'https://tilalr.com');
        $domain = parse_url($appUrl, PHP_URL_HOST) ?? $appUrl;
        $domain = preg_replace('/^admin\./i', '', $domain);

        $messages = [
            'register' => "Your login code is: {$code}\nfor {$domain}",
            'login' => "Your login code is: {$code}\nfor {$domain}",
        ];

        $message = $messages[$type] ?? "Your login code is: {$code}\nfor {$domain}";

        if ($this->mode === 'sms') {
            // Full SMS mode - actually send via SMS provider
            try {
                $result = $this->smsService->send($phone, $message);
                if ($result) {
                    return true;
                }

                // Provider indicated failure
                Log::error('SMS provider reported failure', ['phone' => $phone, 'type' => $type]);
                return false;
            } catch (\Throwable $e) {
                Log::error('SMS provider exception', ['error' => $e->getMessage(), 'phone' => $phone]);
                return false;
            }
        } else {
            // Fixed or random mode - just log
            Log::info("[OTP {$this->mode}] Would send to {$phone}: {$message}");
            return true;
        }
    }

    /**
     * Get current OTP mode for debugging
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Check if in development/fixed mode
     */
    public function isFixedMode(): bool
    {
        return $this->mode === 'fixed';
    }
}
