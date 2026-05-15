<?php

namespace App\Services\Demo;

use App\Enums\PropertyType;

/**
 * Demo description generator. Builds a natural-sounding property blurb from
 * the listing attributes using templated language. This is not a real LLM
 * call — it's a rule-based simulation that is clearly labelled in the UI.
 */
class AiDescriptionService
{
    /**
     * @param array{
     *   title?: string|null,
     *   type?: string|null,
     *   bedrooms?: int|null,
     *   bathrooms?: int|null,
     *   area?: int|float|null,
     *   area_unit?: string|null,
     *   city?: string|null,
     *   furnished?: bool|null,
     *   parking?: bool|null,
     *   nearby_facilities?: array<int, string>|null,
     *   year_built?: int|null,
     * } $attributes
     */
    public function generate(array $attributes): string
    {
        if (! config('estatify.demo.ai_descriptions', true)) {
            return '';
        }

        $type   = $attributes['type'] ?? null;
        $typeLabel = $type
            ? (PropertyType::tryFrom((string) $type)?->label() ?? ucfirst((string) $type))
            : 'property';
        $city   = $attributes['city'] ?? 'a sought-after locality';
        $area   = $attributes['area'] ?? null;
        $unit   = $attributes['area_unit'] ?? 'sqft';
        $beds   = $attributes['bedrooms'] ?? null;
        $baths  = $attributes['bathrooms'] ?? null;
        $year   = $attributes['year_built'] ?? null;
        $facilities = $attributes['nearby_facilities'] ?? [];

        $lead = $this->pick([
            "Welcome to this ".strtolower($typeLabel)." set in {$city}.",
            "Discover a beautifully presented ".strtolower($typeLabel)." in {$city}.",
            "An inviting ".strtolower($typeLabel)." located in {$city} awaits its next owner.",
        ]);

        $specs = [];
        if ($beds)  $specs[] = "{$beds} bedroom".($beds > 1 ? 's' : '');
        if ($baths) $specs[] = "{$baths} bathroom".($baths > 1 ? 's' : '');
        if ($area)  $specs[] = number_format((float) $area).' '.$unit.' of living space';

        $details = empty($specs)
            ? "Thoughtfully designed and ready to move into."
            : "It offers ".$this->joinHuman($specs).'.';

        $extras = [];
        if (! empty($attributes['furnished'])) {
            $extras[] = "comes fully furnished";
        }
        if (! empty($attributes['parking'])) {
            $extras[] = "includes dedicated parking";
        }
        if ($year) {
            $extras[] = "built in {$year} and well maintained";
        }

        $extrasLine = $extras
            ? 'It '.$this->joinHuman($extras).'.'
            : 'Bright, airy interiors make day-to-day living a breeze.';

        $facilityLine = '';
        if (! empty($facilities)) {
            $facilityLine = 'You\'ll find '.$this->joinHuman(array_slice($facilities, 0, 4)).' nearby — handy for everyday errands.';
        }

        $closer = $this->pick([
            'Schedule a viewing to experience it in person.',
            'Get in touch with the agent for a private tour.',
            'Reach out today to discuss availability and next steps.',
        ]);

        return trim(implode(' ', array_filter([$lead, $details, $extrasLine, $facilityLine, $closer])));
    }

    private function pick(array $options): string
    {
        return $options[array_rand($options)];
    }

    private function joinHuman(array $items): string
    {
        $items = array_values(array_filter($items, fn ($v) => $v !== null && $v !== ''));
        if (empty($items)) return '';
        if (count($items) === 1) return $items[0];
        $last = array_pop($items);
        return implode(', ', $items).' and '.$last;
    }
}
