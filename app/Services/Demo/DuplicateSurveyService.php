<?php

namespace App\Services\Demo;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

/**
 * Checks if a survey number has already been used on a different listing.
 * Useful at property submission time as an early-warning signal — the
 * decision still rests with the admin reviewer.
 */
class DuplicateSurveyService
{
    /** @return Collection<int, Property> */
    public function findMatches(?string $survey, ?int $ignorePropertyId = null): Collection
    {
        $survey = trim((string) $survey);
        if ($survey === '') {
            return new Collection();
        }

        $query = Property::query()
            ->where('survey_number', $survey)
            ->with('owner');

        if ($ignorePropertyId) {
            $query->where('id', '!=', $ignorePropertyId);
        }

        return $query->get();
    }

    public function hasDuplicate(?string $survey, ?int $ignorePropertyId = null): bool
    {
        return $this->findMatches($survey, $ignorePropertyId)->isNotEmpty();
    }
}
