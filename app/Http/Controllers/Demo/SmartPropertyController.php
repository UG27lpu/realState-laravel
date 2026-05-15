<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Services\Demo\AiDescriptionService;
use App\Services\Demo\DuplicateSurveyService;
use App\Services\Demo\PricePredictionService;
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

    public function predictPrice(Request $request, PricePredictionService $service): JsonResponse
    {
        $data = $request->validate([
            'area'       => ['required', 'numeric'],
            'city'       => ['required', 'string', 'max:96'],
            'type'       => ['required', 'string'],
            'bedrooms'   => ['nullable', 'integer'],
            'bathrooms'  => ['nullable', 'integer'],
            'furnished'  => ['nullable', 'boolean'],
            'parking'    => ['nullable', 'boolean'],
            'year_built' => ['nullable', 'integer'],
        ]);

        return response()->json($service->estimate($data) + ['demo' => true]);
    }

    public function checkDuplicate(Request $request, DuplicateSurveyService $service): JsonResponse
    {
        $data = $request->validate([
            'survey_number' => ['required', 'string', 'max:64'],
            'ignore_id'     => ['nullable', 'integer'],
        ]);

        $matches = $service->findMatches($data['survey_number'], $data['ignore_id'] ?? null)
            ->map(fn ($p) => [
                'title' => $p->title,
                'city'  => $p->city,
                'owner' => $p->owner?->name,
                'url'   => route('properties.show', $p),
            ]);

        return response()->json([
            'duplicate' => $matches->isNotEmpty(),
            'matches'   => $matches,
            'demo'      => true,
        ]);
    }
}
