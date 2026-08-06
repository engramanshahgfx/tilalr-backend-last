<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TourismDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TourismDestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = TourismDestination::where('active', true);

        if ($request->has('region')) {
            $query->where('region', $request->region);
        }

        $destinations = $query->get()->map(function ($destination) {
            return $this->formatDestination($destination);
        });

        return response()->json([
            'success' => true,
            'data' => $destinations
        ]);
    }

    public function show($slug)
    {
        $destination = TourismDestination::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $this->formatDestination($destination)
        ]);
    }

    public function getByRegion($region)
    {
        $destinations = TourismDestination::where('region', $region)
            ->where('active', true)
            ->get()
            ->map(function ($destination) {
                return $this->formatDestination($destination);
            });

        return response()->json([
            'success' => true,
            'data' => $destinations
        ]);
    }

    public function getRegions()
    {
        $regions = TourismDestination::select('region')
            ->where('active', true)
            ->distinct()
            ->get()
            ->pluck('region');

        return response()->json([
            'success' => true,
            'data' => $regions
        ]);
    }

    private function formatDestination($destination)
    {
        $destination->image_url = $destination->image_url ?? $this->getImageUrl($destination->image);
        return $destination;
    }

    private function getImageUrl($image)
    {
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

    public function getNavbarData()
    {
        $destinations = TourismDestination::where('active', true)->get();

        $grouped = [];
        foreach ($destinations as $dest) {
            $regionRaw = $dest->region ?? 'other';
            $regionKey = strtolower($regionRaw);

            $regionIcons = [
                'europe' => '🌍',
                'asia' => '🌏',
                'africa' => '🌍',
                'australia' => '🌏',
                'america' => '🌎',
                'americas' => '🌎',
                'middle_east' => '🕌',
                'oceania' => '🌏',
            ];

            $regionArabic = [
                'europe' => 'أوروبا',
                'asia' => 'آسيا',
                'africa' => 'أفريقيا',
                'australia' => 'أستراليا ونيوزيلندا',
                'america' => 'أمريكا',
                'americas' => 'الأمريكتان',
                'middle_east' => 'الشرق الأوسط',
                'oceania' => 'أوقيانوسيا',
            ];

            if (!isset($grouped[$regionKey])) {
                $grouped[$regionKey] = [
                    'icon' => $regionIcons[$regionKey] ?? '🌍',
                    'ar' => $regionArabic[$regionKey] ?? ucfirst($regionKey),
                    'countries' => []
                ];
            }

            $grouped[$regionKey]['countries'][] = [
                'en' => $dest->title_en,
                'ar' => $dest->title_ar,
                'slug' => $dest->slug,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $grouped
        ]);
    }
}
