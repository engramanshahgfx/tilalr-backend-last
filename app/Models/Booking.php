<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory, \App\Traits\LogsActivity;

    protected $fillable = [
        'booking_number',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'travel_date',
        'room_type',
        'package_id',
        'package_code',
        'package_title',
        'price',
        'total_amount',
        'status',
        'notes',
        'order_stat',
        'user_id',
        'payment_method',
        'payment_status',
        'payment_id',
        'transaction_id',
        'booking_type',
        'guests',
        'special_requests',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = static::generateBookingNumber();
            }
<<<<<<< Updated upstream
            if (empty($booking->first_name)) {
                $booking->first_name = '';
            }
            if (empty($booking->last_name)) {
                $booking->last_name = '';
            }
=======
>>>>>>> Stashed changes
        });
    }

    public static function generateBookingNumber()
    {
        $prefix = 'BK';
        $number = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $code = $prefix . $number . date('y');

        while (self::where('booking_number', $code)->exists()) {
            $number = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $code = $prefix . $number . date('y');
        }

        return $code;
    }

    public function isTourismOffer()
    {
        return $this->booking_type === 'tourism_offer';
    }

    public function isJamoulaOffer()
    {
        return $this->booking_type === 'jamoula_offer';
    }

    public function isDestination()
    {
        return $this->booking_type === 'destination';
    }
}
