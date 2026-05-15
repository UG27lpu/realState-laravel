<?php

namespace App\Services\Demo;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

/**
 * Rule-based similarity scorer used in place of a real ML recommender.
 * Scores candidate properties against a seed property using a few simple
 * heuristics (same type, similar price range, same city, comparable area).
 */
class RecommendationService
{
    public function similarTo(Property $seed, int $limit = 4): Collection
    {
        $candidates = Property::query()
            ->visible()
            ->where('id', '!=', $seed->id)
            ->with('images')
            ->limit(60)
            ->get();

        return $candidates
            ->map(function (Property $candidate) use ($seed) {
                $candidate->setAttribute('match_score', $this->score($seed, $candidate));
                return $candidate;
            })
            ->sortByDesc('match_score')
            ->take($limit)
            ->values();
    }

    public function forUser(?int $userId, int $limit = 6): Collection
    {
        if (! $userId) {
            return Property::query()->visible()->latest()->with('images')->limit($limit)->get();
        }

        $base = Property::query()
            ->whereIn('id', function ($q) use ($userId) {
                $q->select('property_id')
                  ->from('wishlists')
                  ->where('user_id', $userId);
            })
            ->first();

        if (! $base) {
            return Property::query()->visible()->orderByDesc('view_count')->with('images')->limit($limit)->get();
        }

        return $this->similarTo($base, $limit);
    }

    private function score(Property $a, Property $b): float
    {
        $score = 0.0;

        if ($a->type === $b->type) $score += 4.0;
        if ($a->city === $b->city) $score += 2.5;
        if ($a->status === $b->status) $score += 1.0;

        if ($a->price && $b->price) {
            $delta = abs((float) $a->price - (float) $b->price) / max((float) $a->price, 1);
            $score += max(0, 3.0 - ($delta * 3.0));
        }

        if ($a->area && $b->area) {
            $delta = abs((float) $a->area - (float) $b->area) / max((float) $a->area, 1);
            $score += max(0, 2.0 - ($delta * 2.0));
        }

        if ($a->bedrooms !== null && $b->bedrooms !== null) {
            $score += max(0, 1.5 - abs($a->bedrooms - $b->bedrooms) * 0.5);
        }

        return round($score, 2);
    }
}
