<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function initiateMoyasarPayment(Request $request)
    {
        Log::info('Initiate Moyasar payment:', $request->all());

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|integer|exists:bookings,id',
            'amount' => 'nullable|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $booking = Booking::find($request->booking_id);
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            $rawAmount = $request->amount ?? $booking->total_amount ?? $booking->price ?? 1;
            $amountInHalalas = (int) round($rawAmount * 100);
            if ($amountInHalalas < 100) $amountInHalalas = 100;

            $customerName = trim(($booking->first_name ?? '') . ' ' . ($booking->last_name ?? ''));
            $customerName = preg_replace('/[^A-Za-z\s\.]/', '', $customerName);
            if (empty($customerName) || strlen($customerName) < 2) {
                $customerName = 'Guest User';
            }

            $lang = $request->lang ?? 'en';
            
            // Dynamically detect frontend URL from request origin, referer, or env setting
            $rawOrigin = $request->header('Origin') ?? $request->header('Referer') ?? env('FRONTEND_URL', 'https://tilalr.com');
            $frontendUrl = env('FRONTEND_URL', 'https://tilalr.com');

            if ($rawOrigin && filter_var($rawOrigin, FILTER_VALIDATE_URL)) {
                $parsed = parse_url($rawOrigin);
                if (isset($parsed['scheme']) && isset($parsed['host'])) {
                    $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
                    $frontendUrl = $parsed['scheme'] . '://' . $parsed['host'] . $port;
                }
            }
            $frontendUrl = rtrim($frontendUrl, '/');

            $successUrl = $frontendUrl . '/' . $lang . '/payment-success?booking_id=' . $booking->id;
            $cancelUrl = $frontendUrl . '/' . $lang . '/payment-cancel?booking_id=' . $booking->id;

            $invoiceData = [
                'amount' => $amountInHalalas,
                'currency' => 'SAR',
                'description' => 'Booking #' . ($booking->booking_number ?? $booking->id),
                'callback_url' => $successUrl,
                'back_url' => $cancelUrl,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'customer_name' => $customerName,
                    'customer_email' => $booking->email ?? '',
                ],
            ];

            Log::info('Sending to Moyasar Invoices API:', $invoiceData);

            $response = Http::withBasicAuth(env('MOYASAR_SECRET_KEY'), '')
                ->withOptions([
                    'verify' => false,
                    'timeout' => 60,
                ])
                ->asJson()
                ->post('https://api.moyasar.com/v1/invoices', $invoiceData);

            $status = $response->status();
            $result = $response->json();

            Log::info('Moyasar Invoices response:', ['status' => $status, 'body' => $result]);

            if ($status === 200 || $status === 201) {
                if (isset($result['id'])) {
                    $booking->update([
                        'payment_id' => $result['id'],
                        'payment_status' => 'initiated',
                        'transaction_id' => $result['id'],
                    ]);

                    // Moyasar Hosted Invoice URL (e.g. https://checkout.moyasar.com/invoices/xxxx?lang=en)
                    $paymentUrl = $result['url'] ?? null;
                    if ($paymentUrl && strpos($paymentUrl, 'lang=') === false) {
                        $paymentUrl .= (strpos($paymentUrl, '?') !== false ? '&' : '?') . 'lang=' . $lang;
                    }

                    if ($paymentUrl) {
                        return response()->json([
                            'success' => true,
                            'payment_url' => $paymentUrl,
                            'payment_id' => $result['id'],
                        ]);
                    }

                    return response()->json([
                        'success' => false,
                        'message' => 'Invoice created but no hosted URL returned by Moyasar.',
                        'debug' => $result,
                    ], 500);
                }
            }


            if ($status === 401) {
                $errorMsg = $result['message'] ?? 'Moyasar authentication failed. Please check API keys or IP Whitelisting in Moyasar Dashboard.';
                Log::error('Moyasar 401 Authentication Error:', ['message' => $errorMsg, 'result' => $result]);
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                    'debug' => $result
                ], 401);
            }

            if ($status === 400 && isset($result['type']) && $result['type'] === 'validation_error') {
                Log::warning('Moyasar requires card fields for this account:', $result);
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Moyasar validation failed',
                    'errors' => $result['errors'] ?? null,
                    'debug' => $result,
                ], 400);
            }

            $errorMessage = $result['message'] ?? 'Payment initiation failed';
            if (isset($result['errors'])) {
                $errorDetails = [];
                foreach ($result['errors'] as $key => $value) {
                    $errorDetails[] = $key . ': ' . (is_array($value) ? implode(', ', $value) : $value);
                }
                $errorMessage = implode('; ', $errorDetails);
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'debug' => $result
            ], 400);

        } catch (\Exception $e) {
            Log::error('Payment error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function moyasarWebhook(Request $request)
    {
        Log::info('Moyasar webhook received:', $request->all());

        $payload = $request->all();
        $data = $payload['data'] ?? $payload;

        if (!isset($data['id'])) {
            Log::warning('Invalid webhook payload: missing payment ID');
            return response()->json(['status' => 'error'], 400);
        }

        $paymentId = $data['id'];
        $status = $data['status'] ?? 'unknown';

        $booking = Booking::where('payment_id', $paymentId)->first();

        if (!$booking) {
            Log::warning('Booking not found for payment ID: ' . $paymentId);
            return response()->json(['status' => 'not_found'], 404);
        }

        $paymentStatus = match ($status) {
            'paid', 'captured' => 'paid',
            'failed' => 'failed',
            'refunded' => 'refunded',
            default => $status,
        };

        $bookingStatus = match ($status) {
            'paid', 'captured' => 'confirmed',
            'failed' => 'cancelled',
            default => 'pending',
        };

        $booking->update([
            'payment_status' => $paymentStatus,
            'status' => $bookingStatus,
        ]);

        Log::info('Booking payment status updated:', [
            'booking_id' => $booking->id,
            'payment_id' => $paymentId,
            'status' => $paymentStatus,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function getPaymentStatus($id)
    {
        $booking = Booking::where('id', $id)
            ->orWhere('booking_number', $id)
            ->orWhere('payment_id', $id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'is_paid' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        // If payment status in database is not paid yet, but a payment_id exists, try verifying directly with Moyasar API
        if ($booking->payment_status !== 'paid' && $booking->payment_id) {
            try {
                $moyasarKey = env('MOYASAR_SECRET_KEY');
                if ($moyasarKey) {
                    $response = Http::withBasicAuth($moyasarKey, '')
                        ->withOptions(['verify' => false])
                        ->get("https://api.moyasar.com/v1/payments/{$booking->payment_id}");

                    if ($response->successful()) {
                        $moyasarData = $response->json();
                        $moyasarStatus = strtolower($moyasarData['status'] ?? '');
                        if (in_array($moyasarStatus, ['paid', 'captured'])) {
                            $booking->update([
                                'payment_status' => 'paid',
                                'status' => 'confirmed',
                            ]);
                        } else if ($moyasarStatus === 'failed') {
                            $booking->update([
                                'payment_status' => 'failed',
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Moyasar direct verification check failed: ' . $e->getMessage());
            }
        }

        $isPaid = in_array(strtolower($booking->payment_status ?? ''), ['paid', 'captured', 'completed', 'confirmed']);

        return response()->json([
            'success' => true,
            'is_paid' => $isPaid,
            'data' => [
                'id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'payment_status' => $booking->payment_status,
                'status' => $booking->status,
                'amount' => $booking->total_amount ?? $booking->price,
                'payment_id' => $booking->payment_id,
            ]
        ]);
    }
}
