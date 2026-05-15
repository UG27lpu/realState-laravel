<?php

namespace App\Services;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class PropertySearchService
{
    private const SORTS = [
        'newest'    => ['created_at', 'desc'],
        'oldest'    => ['created_at', 'asc'],
        'price_asc' => ['price', 'asc'],
        'price_desc'=> ['price', 'desc'],
        'area_desc' => ['area', 'desc'],
        'popular'   => ['view_count', 'desc'],
    ];

    /**
     * Apply text search, filters and sorting to the public property listing
     * query, then paginate the results.
     */
    public function paginate(Request $request, int $perPage = 12): LengthAwarePaginator
    {
        $query = Property::query()->visible()->with(['images']);

        $term = trim((string) $request->input('q', ''));
        if ($term !== '') {
            $query->where(function ($w) use ($term) {
                $w->where('title', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhere('address', 'like', "%{$term}%")
                  ->orWhere('city', 'like', "%{$term}%");
            });
        }

        if ($type = $request->input('type')) {
            if (in_array($type, array_keys(PropertyType::options()), true)) {
                $query->where('type', $type);
            }
        }

        if ($status = $request->input('status')) {
            if (in_array($status, array_keys(PropertyStatus::options()), true)) {
                $query->where('status', $status);
            }
        }

        if ($city = $request->input('city')) {
            $query->where('city', 'like', "%{$city}%");
        }

        if ($min = $request->input('price_min')) {
            $query->where('price', '>=', (float) $min);
        }
        if ($max = $request->input('price_max')) {
            $query->where('price', '<=', (float) $max);
        }

        if ($minArea = $request->input('area_min')) {
            $query->where('area', '>=', (float) $minArea);
        }
        if ($maxArea = $request->input('area_max')) {
            $query->where('area', '<=', (float) $maxArea);
        }

        if ($beds = $request->input('bedrooms')) {
            $query->where('bedrooms', '>=', (int) $beds);
        }

        $sort = $request->input('sort', 'newest');
        [$column, $direction] = self::SORTS[$sort] ?? self::SORTS['newest'];
        $query->orderBy($column, $direction);

        return $query->paginate($perPage)->withQueryString();
    }

    public function sortOptions(): array
    {
        return [
            'newest'     => 'Newest first',
            'oldest'     => 'Oldest first',
            'price_asc'  => 'Price (low to high)',
            'price_desc' => 'Price (high to low)',
            'area_desc'  => 'Area (largest first)',
            'popular'    => 'Most viewed',
        ];
    }
}
