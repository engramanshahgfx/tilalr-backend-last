<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'distribution_scope',
        'auto_apply',
        'status',
        'type',
        'value',
        'min_booking_amount',
        'max_discount_amount',
        'is_stackable',
        'category_type',
        'applicable_package_ids',
        'target_cohort',
        'target_emails',
        'max_uses',
        'uses_count',
        'max_uses_per_user',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'auto_apply' => 'boolean',
        'is_stackable' => 'boolean',
        'applicable_package_ids' => 'array',
        'target_emails' => 'array',
        'value' => 'float',
        'min_booking_amount' => 'float',
        'max_discount_amount' => 'float',
        'max_uses' => 'integer',
        'uses_count' => 'integer',
        'max_uses_per_user' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function redemptions()
    {
        return $this->hasMany(CouponRedemption::class);
    }

    public function isExpired(): bool
    {
        if ($this->expires_at && Carbon::now('Asia/Riyadh')->greaterThan($this->expires_at)) {
            return true;
        }
        return false;
    }

    public function isScheduled(): bool
    {
        if ($this->starts_at && Carbon::now('Asia/Riyadh')->lessThan($this->starts_at)) {
            return true;
        }
        return false;
    }

    public function isValidForBooking(float $bookingAmount, ?int $userId = null, ?string $packageType = null, ?int $packageId = null, ?string $email = null): array
    {
        if ($this->status !== 'active') {
            return ['valid' => false, 'message' => 'This promo code is currently inactive.'];
        }

        if ($this->isScheduled()) {
            return ['valid' => false, 'message' => 'This promo code is not active yet.'];
        }

        if ($this->isExpired()) {
            return ['valid' => false, 'message' => 'This promo code has expired.'];
        }

        if ($this->max_uses !== null && $this->uses_count >= $this->max_uses) {
            return ['valid' => false, 'message' => 'This promo code has reached its maximum global limit.'];
        }

        if ($this->min_booking_amount !== null && $bookingAmount < $this->min_booking_amount) {
            return ['valid' => false, 'message' => "Minimum booking amount of SAR {$this->min_booking_amount} is required to use this code."];
        }

        // Check user redemption limit
        if ($this->max_uses_per_user !== null) {
            $userUses = 0;
            if ($userId) {
                $userUses = $this->redemptions()->where('user_id', $userId)->count();
            } elseif ($email) {
                $userUses = $this->redemptions()->where('email', $email)->count();
            }
            if ($userUses >= $this->max_uses_per_user) {
                return ['valid' => false, 'message' => 'You have already reached the maximum usage limit for this promo code.'];
            }
        }

        // Check category restriction
        if ($this->category_type !== 'all' && $packageType && $this->category_type !== $packageType) {
            return ['valid' => false, 'message' => 'This promo code is not valid for the selected offer category.'];
        }

        // Check specific package IDs
        if (!empty($this->applicable_package_ids) && $packageId) {
            $allowed = array_map('strval', $this->applicable_package_ids);
            $packageStr = (string)$packageId;
            $possibleMatches = [
                $packageStr,
                "dest_{$packageStr}",
                "offer_{$packageStr}",
                "jamoula_{$packageStr}"
            ];
            if (empty(array_intersect($possibleMatches, $allowed))) {
                return ['valid' => false, 'message' => 'This promo code is not valid for the selected package.'];
            }
        }

        // Check targeted emails cohort
        if ($this->target_cohort === 'specified_users' && !empty($this->target_emails)) {
            if (!$email || !in_array(strtolower($email), array_map('strtolower', $this->target_emails))) {
                return ['valid' => false, 'message' => 'This promo code is restricted to specific customer accounts.'];
            }
        }

        // Calculate discount amount
        $discountAmount = 0.0;
        if ($this->type === 'percentage') {
            $discountAmount = ($this->value / 100) * $bookingAmount;
            if ($this->max_discount_amount !== null && $discountAmount > $this->max_discount_amount) {
                $discountAmount = $this->max_discount_amount;
            }
        } else {
            $discountAmount = min($this->value, $bookingAmount);
        }

        return [
            'valid' => true,
            'discount_amount' => round($discountAmount, 2),
            'final_total' => max(0, round($bookingAmount - $discountAmount, 2)),
            'message' => 'Promo code applied successfully!'
        ];
    }
}
