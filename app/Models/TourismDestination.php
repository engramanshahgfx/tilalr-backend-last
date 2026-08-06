<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TourismDestination extends Model
{
    use HasFactory;

    protected $table = 'tourism_destinations';

    protected $appends = ['image_url'];

    protected $fillable = [
        'slug',
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'long_description_en',
        'long_description_ar',
        'location_en',
        'location_ar',
        'duration_en',
        'duration_ar',
        'price',
        'rating',
        'image',
        'images',
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
        'region',
        'trip_code',
        'available_to',
        'person_prices',
        'active',
    ];

    protected $casts = [
        'images' => 'array',
        'features_en' => 'array',
        'features_ar' => 'array',
        'includes_en' => 'array',
        'includes_ar' => 'array',
        'not_includes_en' => 'array',
        'not_includes_ar' => 'array',
        'itinerary_en' => 'array',
        'itinerary_ar' => 'array',
        'basic_info' => 'array',
        'contact_info' => 'array',
        'payment_methods' => 'array',
        'person_prices' => 'array',
        'price' => 'decimal:2',
        'rating' => 'float',
        'available_to' => 'date',
        'active' => 'boolean',
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


    private function decodeJsonArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    private function prepareJsonValue($value): ?string
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $value;
            }
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return null;
    }

    public function getBasicInfoAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setBasicInfoAttribute($value): void
    {
        $this->attributes['basic_info'] = $this->prepareJsonValue($value);
    }

    public function getContactInfoAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setContactInfoAttribute($value): void
    {
        $this->attributes['contact_info'] = $this->prepareJsonValue($value);
    }

    public function getPaymentMethodsAttribute($value): array
    {
        return $this->decodeJsonArray($value);
    }

    public function setPaymentMethodsAttribute($value): void
    {
        $this->attributes['payment_methods'] = $this->prepareJsonValue($value);
    }

    // Get localized field
    public function getLocalized($field, $lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        $fieldKey = $field . '_' . $lang;
        return $this->$fieldKey ?? $this->{$field . '_en'} ?? $this->$field ?? null;
    }

    // Accessors for Arabic content
    public function getTitleArAttribute($value)
    {
        return $value ?? $this->title_en;
    }

    public function getDescriptionArAttribute($value)
    {
        return $value ?? $this->description_en;
    }

    public function getLongDescriptionArAttribute($value)
    {
        return $value ?? $this->long_description_en;
    }

    public function getFeaturesArAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? $this->features_en ?? [];
        }
        return $value ?? $this->features_en ?? [];
    }

    public function getIncludesArAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? $this->includes_en ?? [];
        }
        return $value ?? $this->includes_en ?? [];
    }

    public function getNotIncludesArAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? $this->not_includes_en ?? [];
        }
        return $value ?? $this->not_includes_en ?? [];
    }

    public function getItineraryArAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? $this->itinerary_en ?? [];
        }
        return $value ?? $this->itinerary_en ?? [];
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

        if (!str_starts_with($clean, 'tourism/')) {
            if (Storage::disk('public')->exists('tourism/' . $clean)) {
                return asset('storage/tourism/' . $clean);
            }
        }

        if (file_exists(public_path('storage/' . $clean))) {
            return asset('storage/' . $clean);
        }

        if (file_exists(public_path('storage/tourism/' . $clean))) {
            return asset('storage/tourism/' . $clean);
        }

        return asset('storage/' . $clean);
    }
}
