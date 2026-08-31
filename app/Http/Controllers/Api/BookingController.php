<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourismDestination;
use App\Models\TourismOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function guestStore(Request $request)
    {
        \Log::info('=== GUEST BOOKING REQUEST ===');
        \Log::info('Request data:', $request->all());

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'travel_date' => 'required|date_format:Y-m-d',
            'room_type' => 'nullable|string',
            'package_id' => 'nullable|string',
            'package_code' => 'nullable|string',
            'package_title' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|in:credit_card,bank_transfer',
            'booking_type' => 'nullable|in:destination,tourism_offer',
            'guests' => 'nullable|integer|min:1',
            'special_requests' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            \Log::error('Validation failed:', $errors);
            \Log::error('Full request body:', [
                'travel_date' => $request->travel_date,
                'travel_date_type' => gettype($request->travel_date),
                'all_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors,
                'debug' => ['received_data' => $request->all()]
            ], 422);
        }

        $bookingType = $request->booking_type ?? 'destination';
        $package = null;
        $packageTitle = 'Unknown Package';
        $doubleRoomPrice = 0;
        $singleRoomPrice = 0;
        $basicInfo = [];

        if ($bookingType === 'tourism_offer') {
            // For tourism offers, try to find in database
            $package = TourismOffer::where('id', $request->package_id)
                ->orWhere('slug', $request->package_id)
                ->first();

            if ($package) {
                $packageTitle = $package->title_en ?? $package->title_ar ?? 'Tourism Offer';
                $packagePrice = floatval($package->price ?? 0);
            } else {
                $packageTitle = $request->package_title ?? 'Tourism Offer';
                $packagePrice = floatval($request->price ?? $request->total_amount ?? 0);
            }
        } else {
            // For destinations, try TourismDestination first, then TourismOffer fallback
            $package = TourismDestination::where('id', $request->package_id)
                ->orWhere('slug', $request->package_id)
                ->first();

            if (!$package) {
                $package = TourismOffer::where('id', $request->package_id)
                    ->orWhere('slug', $request->package_id)
                    ->first();
            }

            if ($package) {
                $packageTitle = $package->title_en ?? $package->title_ar ?? $package->title ?? 'Destination';
                $packagePrice = floatval($package->price ?? 0);
            } else {
                // Fallback to requested package_title / price if package is not in DB table
                $packageTitle = $request->package_title ?? 'Tourism Package';
                $packagePrice = floatval($request->price ?? $request->total_amount ?? 0);
            }
        }

        $guests = max(1, intval($request->guests ?? 1));

        if ($request->filled('total_amount') && floatval($request->total_amount) > 0) {
            $totalAmount = floatval($request->total_amount);
        } else {
            $totalAmount = $packagePrice * $guests;
        }
        $price = $packagePrice > 0 ? $packagePrice : ($totalAmount / $guests);

        if ($totalAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Booking total amount must be greater than zero.',
                'errors' => ['total_amount' => ['Invalid total amount.']],
            ], 422);
        }

        $packageIdStr = (string) ($package ? $package->id : ($request->package_id ?? '1'));

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'travel_date' => $request->travel_date,
            'room_type' => $request->room_type,
            'package_id' => $packageIdStr,
            'package_code' => $request->package_code ?? 'PKG-' . $packageIdStr,
            'package_title' => $packageTitle,
            'price' => $price,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'order_stat' => 'New',
            'user_id' => $request->user()?->id ?? null,
            'notes' => $request->notes,
            'payment_method' => $request->payment_method ?? 'credit_card',
            'payment_status' => 'pending',
            'booking_type' => $bookingType,
            'guests' => $request->guests ?? 1,
            'special_requests' => $request->special_requests ?? '',
        ]);

        // Process coupon redemption if coupon code was supplied
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();
            if ($coupon) {
                $discountAmount = floatval($request->discount_amount ?? 0);
                \App\Models\CouponRedemption::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $request->user()?->id ?? null,
                    'booking_id' => $booking->id,
                    'email' => $request->email,
                    'discount_amount' => $discountAmount,
                    'booking_amount' => $totalAmount,
                ]);
                $coupon->increment('uses_count');
            }
        }

        \Log::info('Guest booking created:', [
            'id' => $booking->id,
            'number' => $booking->booking_number,
            'type' => $booking->booking_type,
            'payment_method' => $booking->payment_method,
            'price' => $booking->price,
            'total_amount' => $booking->total_amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking
        ], 201);
    }

    /**
     * Store booking for authenticated / default route POST /api/bookings
     */
    public function store(Request $request)
    {
        return $this->guestStore($request);
    }

    /**
     * Get list of bookings
     */
    public function index(Request $request)
    {
        $query = Booking::query();

        if ($request->user() && !($request->user()->is_admin ?? false)) {
            $query->where('user_id', $request->user()->id);
        } elseif ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * Get single booking details
     */
    public function show(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->orWhere('booking_number', $id)->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    /**
     * Update booking details
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        $booking->update($request->only([
            'first_name', 'last_name', 'email', 'mobile', 'travel_date',
            'room_type', 'notes', 'status', 'payment_status', 'order_stat',
            'special_requests', 'total_amount'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully',
            'data' => $booking
        ]);
    }

    /**
     * Delete booking
     */
    public function destroy(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully'
        ]);
    }

    /**
     * Check booking status
     */
    public function checkStatus(Request $request, $id = null)
    {
        $bookingId = $id ?? $request->input('booking_id') ?? $request->input('id') ?? $request->input('booking_number');
        $booking = null;
        if ($bookingId) {
            $booking = Booking::where('id', $bookingId)->orWhere('booking_number', $bookingId)->first();
        }
        if (!$booking && $request->input('email')) {
            $booking = Booking::where('email', $request->input('email'))->orderBy('created_at', 'desc')->first();
        }

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'data' => $booking
        ]);
    }

    public function paymentDetails($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }
        return response()->json([
            'success' => true,
            'payment_id' => $booking->payment_id,
            'amount' => $booking->total_amount ?? $booking->price,
            'publishable_key' => config('services.moyasar.publishable_key') ?? env('MOYASAR_PUBLISHABLE_KEY'),
        ]);
    }
}
