<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    /**
     * Apply and validate a promo coupon code.
     * POST /api/v1/coupons/apply
     */
    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'booking_amount' => 'required|numeric|min:0.01',
            'package_type' => 'nullable|string',
            'package_id' => 'nullable|integer',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $code = strtoupper(trim($request->code));
        $bookingAmount = (float) $request->booking_amount;
        $userId = auth('sanctum')->check() ? auth('sanctum')->id() : null;
        $packageType = $request->package_type;
        $packageId = $request->package_id;
        $email = $request->email;

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid promo code. Please check and try again.',
            ], 404);
        }

        $result = $coupon->isValidForBooking(
            bookingAmount: $bookingAmount,
            userId: $userId,
            packageType: $packageType,
            packageId: $packageId,
            email: $email
        );

        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'code' => $coupon->code,
            'discount_type' => $coupon->type,
            'discount_value' => $coupon->value,
            'discount_amount' => $result['discount_amount'],
            'subtotal' => round($bookingAmount, 2),
            'final_total' => $result['final_total'],
            'message' => $result['message'],
        ]);
    }
}
