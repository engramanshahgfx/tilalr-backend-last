<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class JamoulaOffer extends Model
{
    use HasFactory, \App\Traits\LogsActivity;

    protected $table = 'jamoula_offers';

    protected $appends = ['image_url'];

    protected $fillable = [
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'long_description_en',
        'long_description_ar',
        'slug',
        'image',
        'gallery',
        'price',
        'original_price',
        'discount',
        'rating',
        'duration_en',
        'duration_ar',
        'location_en',
        'location_ar',
        'group_size_en',
        'group_size_ar',
        'features_en',
        'features_ar',
        'includes_en',
        'includes_ar',
        'not_includes_en',
        'not_includes_ar',
        'itinerary_en',
        'itinerary_ar',
        'basic_info',
        'contact_info',
        'payment_methods',
        'type',
        'type_ar',
        'active',
        'popular',
        'limited',
        'region',
        'country',
        'city',
        'meta_title_en',
        'meta_title_ar',
        'meta_description_en',
        'meta_description_ar',
        'meta_keywords_en',
        'meta_keywords_ar',
        'person_prices',
        'tourism_offer_id',
        'tourism_destination_id',
    ];

    protected $casts = [
        'gallery'         => 'array',
        'basic_info'      => 'array',
        'contact_info'    => 'array',
        'payment_methods' => 'array',
        'person_prices'   => 'array',
        'active'          => 'boolean',
        'popular'         => 'boolean',
        'limited'         => 'boolean',
        'price'           => 'decimal:2',
        'original_price'  => 'decimal:2',
        'rating'          => 'float',
        'tourism_offer_id'=> 'integer',
        'tourism_destination_id'=> 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function ($model) {
            if (!empty($model->person_prices) && is_array($model->person_prices)) {
                $firstOffer = $model->person_prices[0] ?? null;
                if ($firstOffer && isset($firstOffer['price'])) {
                    if (empty($model->price) || floatval($model->price) == 0) {
                        $model->price = floatval($firstOffer['price']);
                    }
                }
            }
        });
    }

    public function getImageUrlAttribute()
    {
        $image = $this->image;
        if (!$image) {
            return null;
        }

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        $clean = ltrim($image, '/');
        if (str_starts_with($clean, 'storage/')) {
            $clean = substr($clean, 8);
        }

        if (Storage::disk('public')->exists($clean)) {
            return asset('storage/' . $clean);
        }

        foreach (['jamoula-offers/', 'offers/', 'tourism/'] as $folder) {
            if (!str_starts_with($clean, $folder)) {
                if (Storage::disk('public')->exists($folder . $clean)) {
                    return asset('storage/' . $folder . $clean);
                }
            }
        }

        if (file_exists(public_path('storage/' . $clean))) {
            return asset('storage/' . $clean);
        }

        return asset('storage/' . $clean);
    }

    public function tourismOffer()
    {
        return $this->belongsTo(TourismOffer::class, 'tourism_offer_id');
    }

    public function tourismDestination()
    {
        return $this->belongsTo(TourismDestination::class, 'tourism_destination_id');
    }
}
