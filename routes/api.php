<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TourismDestinationController;
use App\Http\Controllers\Api\TourismOfferController;
use App\Http\Controllers\Api\JamoulaOfferController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\TestDataController;
use App\Http\Controllers\Api\CustomPaymentOfferController;
use App\Http\Controllers\Api\EvisaController;
use App\Http\Controllers\Api\VisaCountryController;
use App\Http\Controllers\Api\SchengenController;
use App\Http\Controllers\Api\SpecialOfferController;
use App\Http\Controllers\Api\InternetPackageRequestController;
use App\Http\Controllers\Api\PrivateJetRequestController;
use App\Http\Controllers\Api\CouponController;

// Public Health & Testing (basic status)
Route::get('/test', function () {
    return response()->json(['status' => 'ok', 'message' => 'API routing works!']);
});
Route::get('/health', [HealthController::class, 'check']);
Route::get('/health/db', [HealthController::class, 'dbTest']);

// Public Coupon Check
Route::post('/coupons/apply', [CouponController::class, 'apply']);
Route::post('/v1/coupons/apply', [CouponController::class, 'apply']);

// Authentication (Public)
Route::post('/register', [AuthController::class, 'register']);
Route::get('/users/exists', [AuthController::class, 'emailExists']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/send-otp', [\App\Http\Controllers\Api\OtpController::class, 'send']);
Route::post('/auth/verify-otp', [\App\Http\Controllers\Api\OtpController::class, 'verify']);
Route::post('/auth/reset-password', [\App\Http\Controllers\Api\OtpController::class, 'resetPassword']);

// Public Tourism Destinations
Route::prefix('tourism-destinations')->group(function () {
    Route::get('/', [TourismDestinationController::class, 'index']);
    Route::get('/navbar', [TourismDestinationController::class, 'getNavbarData']);
    Route::get('/regions', [TourismDestinationController::class, 'getRegions']);
    Route::get('/region/{region}', [TourismDestinationController::class, 'getByRegion']);
    Route::get('/{slug}', [TourismDestinationController::class, 'show']);
});

// Public Content & Catalog Browsing
Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/{slug}', [PageController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{slug}', [ServiceController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/trips', [TripController::class, 'index']);
Route::get('/trips/{slug}', [TripController::class, 'show']);
Route::get('/trips/{slug}/blocked-dates', [TripController::class, 'getBlockedDates']);
Route::get('/cities', [CityController::class, 'index']);
Route::get('/cities/{slug}', [CityController::class, 'show']);
Route::get('/testimonials', [TestimonialController::class, 'index']);
Route::get('/testimonials/{id}', [TestimonialController::class, 'show']);
Route::get('/settings', [SettingController::class, 'index']);
Route::get('/settings/{key}', [SettingController::class, 'show']);

// Public Tourism & Jamoula Offers
Route::get('/tourism-offers', [TourismOfferController::class, 'index']);
Route::get('/tourism-offers/{id}', [TourismOfferController::class, 'show']);
Route::get('/jamoula-offers', [JamoulaOfferController::class, 'index']);
Route::get('/jamoula-offers/{id}', [JamoulaOfferController::class, 'show']);
Route::get('/banners', [\App\Http\Controllers\Api\BannerController::class, 'index']);
Route::get('/headerbanners', [\App\Http\Controllers\Api\HeaderBannerController::class, 'index']);
Route::get('/partners', [\App\Http\Controllers\Api\PartnerController::class, 'index']);
Route::get('/special-offers', [SpecialOfferController::class, 'index']);
Route::get('/special-offers/simple', [SpecialOfferController::class, 'simple']);
Route::get('/visa-countries', [VisaCountryController::class, 'index']);
Route::get('/visa-countries/{slug}', [VisaCountryController::class, 'show']);

// Guest Submissions (Public Form Submissions)
Route::post('/internet-packages', [InternetPackageRequestController::class, 'store']);
Route::post('/private-jet-requests', [PrivateJetRequestController::class, 'store']);
Route::post('/visa-applications', [App\Http\Controllers\Api\SaudiVisaController::class, 'store']);
Route::post('/schengen-applications', [SchengenController::class, 'store']);
Route::post('/evisa-applications', [EvisaController::class, 'store']);
Route::post('/bookings/guest', [BookingController::class, 'guestStore']);
Route::get('/bookings/{id}/status', [BookingController::class, 'checkStatus']);
Route::get('/bookings/{id}/payment-details', [BookingController::class, 'paymentDetails']);
Route::post('/reservations', [ReservationController::class, 'store']);
Route::post('/reservations/check-status', [ReservationController::class, 'checkStatus']);
Route::post('/contact', [App\Http\Controllers\Api\ContactController::class, 'store']);

// Custom Payment Offers Links (Public view & callbacks for customer payment flow)
Route::get('/custom-payment-offers/{uniqueLink}', [CustomPaymentOfferController::class, 'show']);
Route::post('/custom-payment-offers/{uniqueLink}/payment-success', [CustomPaymentOfferController::class, 'paymentSuccess']);
Route::get('/custom-payment-offers/{uniqueLink}/payment-success', [CustomPaymentOfferController::class, 'paymentSuccess']);
Route::post('/custom-payment-offers/{uniqueLink}/payment-failed', [CustomPaymentOfferController::class, 'paymentFailed']);
Route::post('/webhooks/moyasar/custom-payment', [CustomPaymentOfferController::class, 'moyasarWebhook']);

// Payment Gateway Webhooks & Callbacks
Route::match(['GET', 'POST'], '/payments/webhook/moyasar', [PaymentController::class, 'moyasarWebhook']);
Route::get('/payments/callback', [PaymentController::class, 'callback']);
Route::get('/payments/status/{id}', [PaymentController::class, 'getPaymentStatus']);
Route::post('/payments/moyasar/initiate', [PaymentController::class, 'initiateMoyasarPayment']);

// ============================================
// PROTECTED ROUTES (Require Authentication - auth:sanctum)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    // Authenticated User Profile & Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Authenticated User Bookings & Payments
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
    Route::post('/bookings/check-status', [BookingController::class, 'checkStatus']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/my-reservations', [ReservationController::class, 'myReservations']);
    Route::post('/payments/initiate', [PaymentController::class, 'initiate']);
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);

    // Sensitive Data Listings (Require Authentication)
    Route::get('/visa-applications', [App\Http\Controllers\Api\SaudiVisaController::class, 'index']);
    Route::get('/visa-applications/{id}', [App\Http\Controllers\Api\SaudiVisaController::class, 'show']);
    Route::get('/schengen-applications', [SchengenController::class, 'index']);
    Route::get('/schengen-applications/{id}', [SchengenController::class, 'show']);
    Route::get('/evisa-applications', [EvisaController::class, 'index']);
    Route::get('/evisa-applications/{id}', [EvisaController::class, 'show']);

    // SMS System Management & Internal Test Data
    Route::post('/test-data/create-users', [TestDataController::class, 'createTestUsers']);
    Route::prefix('sms')->group(function () {
        Route::get('/status', [\App\Http\Controllers\Api\SmsController::class, 'status']);
        Route::post('/test', [\App\Http\Controllers\Api\SmsController::class, 'sendTest']);
        Route::get('/taqnyat/system', [\App\Http\Controllers\Api\SmsController::class, 'taqnyatSystem']);
        Route::get('/taqnyat/balance', [\App\Http\Controllers\Api\SmsController::class, 'taqnyatBalance']);
        Route::get('/taqnyat/senders', [\App\Http\Controllers\Api\SmsController::class, 'taqnyatSenders']);
        Route::get('/taqnyat/test', [\App\Http\Controllers\Api\SmsController::class, 'taqnyatFullTest']);
        Route::post('/taqnyat/send', [\App\Http\Controllers\Api\SmsController::class, 'taqnyatSend']);
    });

    // Administrative API Endpoints
    Route::prefix('admin')->group(function () {
        Route::post('/offers', [TourismOfferController::class, 'index']);
        Route::post('/pages', [PageController::class, 'store']);
        Route::put('/pages/{id}', [PageController::class, 'update']);
        Route::delete('/pages/{id}', [PageController::class, 'destroy']);
        Route::post('/services', [ServiceController::class, 'store']);
        Route::put('/services/{id}', [ServiceController::class, 'update']);
        Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        Route::post('/trips', [TripController::class, 'store']);
        Route::put('/trips/{id}', [TripController::class, 'update']);
        Route::delete('/trips/{id}', [TripController::class, 'destroy']);
        Route::put('/trips/{slug}/blocked-dates', [TripController::class, 'updateBlockedDates']);
        Route::post('/cities', [CityController::class, 'store']);
        Route::put('/cities/{id}', [CityController::class, 'update']);
        Route::delete('/cities/{id}', [CityController::class, 'destroy']);
        Route::post('/testimonials', [TestimonialController::class, 'store']);
        Route::put('/testimonials/{id}', [TestimonialController::class, 'update']);
        Route::delete('/testimonials/{id}', [TestimonialController::class, 'destroy']);
        Route::post('/settings', [SettingController::class, 'store']);
        Route::put('/settings/{key}', [SettingController::class, 'update']);
        Route::delete('/settings/{key}', [SettingController::class, 'destroy']);

        // Admin Reservation Management
        Route::get('/reservations', [ReservationController::class, 'index']);
        Route::get('/reservations/statistics', [ReservationController::class, 'statistics']);
        Route::get('/reservations/{id}', [ReservationController::class, 'show']);
        Route::put('/reservations/{id}', [ReservationController::class, 'update']);
        Route::post('/reservations/{id}/mark-contacted', [ReservationController::class, 'markContacted']);
        Route::post('/reservations/{id}/convert-to-booking', [ReservationController::class, 'convertToBooking']);
        Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

        // Custom Payment Offers Management
        Route::post('/custom-payment-offers', [CustomPaymentOfferController::class, 'create']);
        Route::get('/custom-payment-offers', [CustomPaymentOfferController::class, 'list']);
        Route::delete('/custom-payment-offers/{id}', [CustomPaymentOfferController::class, 'delete']);

        // Schengen & E-Visa Admin Management
        Route::put('/schengen-applications/{id}/status', [SchengenController::class, 'updateStatus']);
        Route::put('/evisa-applications/{id}/status', [EvisaController::class, 'updateStatus']);
    });
});

