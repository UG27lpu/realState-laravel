<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Services\Demo\AiDescriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * AJAX endpoints powering the demo "smart" widgets. All responses include a
 * flag highlighting that the data is simulated.
 */
class SmartPropertyController extends Controller
{
    public function describe(Request $request, AiDescriptionService $ai): JsonResponse
    {
        $data = $request->validate([
            'title'      => ['nullable', 'string', 'max:160'],
            'type'       => ['nullable', 'string'],
            'city'       => ['nullable', 'string'],
            'bedrooms'   => ['nullable', 'integer'],
            'bathrooms'  => ['nullable', 'integer'],
            'area'       => ['nullable', 'numeric'],
            'area_unit'  => ['nullable', 'string'],
            'furnished'  => ['nullable', 'boolean'],
            'parking'    => ['nullable', 'boolean'],
            'year_built' => ['nullable', 'integer'],
            'nearby_facilities' => ['nullable', 'array'],
        ]);

        return response()->json([
            'description' => $ai->generate($data),
            'demo'        => true,
        ]);
    }
}
