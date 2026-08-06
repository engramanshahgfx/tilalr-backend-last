<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TourismOffer extends Model
{
    use HasFactory, \App\Traits\LogsActivity;

    protected $table = 'tourism_offers';

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
    ];

    protected $casts = [
        // gallery, basic_info, contact_info, payment_methods are normal JSON
        'gallery'         => 'array',
        'basic_info'      => 'array',
        'contact_info'    => 'array',
        'payment_methods' => 'array',
        'person_prices'   => 'array',
        'active'          => 'boolean',
        'popular'         => 'boolean',
        'limited'         => 'boolean',
        // NOTE: features_en/ar, includes_en/ar, not_includes_en/ar, itinerary_en/ar
        // are stored as DOUBLE-encoded JSON strings and handled by explicit accessors/mutators below.
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

    // =====================
    // Helper: decode possibly double-encoded JSON into array
    // =====================
    private function decodeJsonArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            // First decode (outer)
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_array($decoded)) {
                    return $decoded;
                }
                // Double-encoded: outer decode gives a string, decode again
                if (is_string($decoded)) {
                    $decoded2 = json_decode($decoded, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded2)) {
                        return $decoded2;
                    }
                }
            }
        }

        return [];
    }

    private function prepareJsonValue($value): ?string
    {
        if (is_array($value) || is_object($value)) {
            return json_encode(array_values((array) $value));
        }

        if (is_string($value)) {
            // Try to normalise: decode, re-encode cleanly
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_array($decoded)) {
                    return json_encode(array_values($decoded));
                }
                // Double-encoded
                if (is_string($decoded)) {
                    $decoded2 = json_decode($decoded, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded2)) {
                        return json_encode(array_values($decoded2));
                    }
                }
            }
            return $value;
        }

        return null;
    }

    // =====================
    // features_en
    // =====================
    public function getFeaturesEnAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setFeaturesEnAttribute($value): void
    {
        $this->attributes['features_en'] = $this->prepareJsonValue($value);
    }

    // =====================
    // features_ar
    // =====================
    public function getFeaturesArAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setFeaturesArAttribute($value): void
    {
        $this->attributes['features_ar'] = $this->prepareJsonValue($value);
    }

    // =====================
    // includes_en
    // =====================
    public function getIncludesEnAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setIncludesEnAttribute($value): void
    {
        $this->attributes['includes_en'] = $this->prepareJsonValue($value);
    }

    // =====================
    // includes_ar
    // =====================
    public function getIncludesArAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setIncludesArAttribute($value): void
    {
        $this->attributes['includes_ar'] = $this->prepareJsonValue($value);
    }

    // =====================
    // not_includes_en
    // =====================
    public function getNotIncludesEnAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setNotIncludesEnAttribute($value): void
    {
        $this->attributes['not_includes_en'] = $this->prepareJsonValue($value);
    }

    // =====================
    // not_includes_ar
    // =====================
    public function getNotIncludesArAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setNotIncludesArAttribute($value): void
    {
        $this->attributes['not_includes_ar'] = $this->prepareJsonValue($value);
    }

    // =====================
    // itinerary_en
    // =====================
    public function getItineraryEnAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setItineraryEnAttribute($value): void
    {
        $this->attributes['itinerary_en'] = $this->prepareJsonValue($value);
    }

    // =====================
    // itinerary_ar
    // =====================
    public function getItineraryArAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setItineraryArAttribute($value): void
    {
        $this->attributes['itinerary_ar'] = $this->prepareJsonValue($value);
    }

    // =====================
    // basic_info (uses $casts => 'array' but override setter for safety)
    // =====================
    public function setBasicInfoAttribute($value): void
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['basic_info'] = json_encode($value);
        } elseif (is_string($value)) {
            $this->attributes['basic_info'] = $value;
        } else {
            $this->attributes['basic_info'] = null;
        }
    }

    // =====================
    // contact_info
    // =====================
    public function setContactInfoAttribute($value): void
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['contact_info'] = json_encode($value);
        } elseif (is_string($value)) {
            $this->attributes['contact_info'] = $value;
        } else {
            $this->attributes['contact_info'] = null;
        }
    }

    // =====================
    // payment_methods
    // =====================
    public function setPaymentMethodsAttribute($value): void
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['payment_methods'] = json_encode(array_values((array) $value));
        } elseif (is_string($value)) {
            $this->attributes['payment_methods'] = $value;
        } else {
            $this->attributes['payment_methods'] = null;
        }
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

        if (!str_starts_with($clean, 'offers/')) {
            if (Storage::disk('public')->exists('offers/' . $clean)) {
                return asset('storage/offers/' . $clean);
            }
        }

        if (file_exists(public_path('storage/' . $clean))) {
            return asset('storage/' . $clean);
        }

        if (file_exists(public_path('storage/offers/' . $clean))) {
            return asset('storage/offers/' . $clean);
        }

        return asset('storage/' . $clean);
    }
}
