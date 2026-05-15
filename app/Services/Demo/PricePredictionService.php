<?php

namespace App\Services\Demo;

use App\Enums\PropertyType;
use App\Models\Property;

/**
 * Demo price estimator. Combines a per-city base rate with bedroom/bathroom
 * uplifts and a small adjustment for furnishing/parking. The output is for
 * educational comparison only — every consumer of this service is required
 * to display the demo tag alongside the estimate.
 */
class PricePredictionService
{
    /** Per-city ballpark sqft rate in INR for the demo. */
    private const CITY_RATES = [
        'Bengaluru' => 8500,
        'Mumbai'    => 22000,
        'Pune'      => 7200,
        'Hyderabad' => 6800,
        'Delhi NCR' => 12500,
        'Chennai'   => 7000,
        'Kolkata'   => 6200,
        'Ahmedabad' => 5800,
    ];

    private const TYPE_MULTIPLIER = [
        'land'       => 0.55,
        'house'      => 1.10,
        'apartment'  => 1.00,
        'commercial' => 1.25,
    ];

    /**
     * @return array{
     *   estimate: float,
     *   low: float,
     *   high: float,
     *   per_sqft: float,
     *   note: string
     * }
     */
    public function estimate(array $attributes): array
    {
        $area    = (float) ($attributes['area'] ?? 0);
        $city    = $attributes['city'] ?? null;
        $type    = $attributes['type'] ?? PropertyType::Apartment->value;
        $beds    = (int) ($attributes['bedrooms'] ?? 0);
        $baths   = (int) ($attributes['bathrooms'] ?? 0);
        $furnish = (bool) ($attributes['furnished'] ?? false);
        $parking = (bool) ($attributes['parking'] ?? false);
        $year    = (int) ($attributes['year_built'] ?? date('Y') - 5);

        $baseRate = self::CITY_RATES[$city] ?? 6000;
        $typeMult = self::TYPE_MULTIPLIER[$type] ?? 1.0;
        $rate     = $baseRate * $typeMult;

        $ageDiscount = max(0.0, min(0.18, ((int) date('Y') - $year) * 0.005));
        $rate *= (1 - $ageDiscount);

        $core = $rate * max($area, 200);

        $core += $beds * 250_000;
        $core += $baths * 90_000;

        if ($furnish) $core *= 1.05;
        if ($parking) $core *= 1.03;

        $estimate = round($core, -3);

        return [
            'estimate' => $estimate,
            'low'      => round($estimate * 0.92, -3),
            'high'     => round($estimate * 1.08, -3),
            'per_sqft' => round($rate, 2),
            'note'     => 'Demo only — not a certified appraisal.',
        ];
    }

    public function forProperty(Property $property): array
    {
        return $this->estimate([
            'area'       => $property->area,
            'city'       => $property->city,
            'type'       => $property->type?->value,
            'bedrooms'   => $property->bedrooms,
            'bathrooms'  => $property->bathrooms,
            'furnished'  => $property->furnished,
            'parking'    => $property->parking,
            'year_built' => $property->year_built,
        ]);
    }
}
