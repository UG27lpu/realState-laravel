<?php

namespace App\Services;

use App\Enums\ApprovalStatus;
use App\Enums\PropertyType;
use App\Models\Appointment;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function summary(): array
    {
        return [
            'totalUsers'        => User::query()->count(),
            'totalAgents'       => User::role('agent')->count(),
            'totalAdmins'       => User::role('admin')->count(),
            'totalProperties'   => Property::query()->count(),
            'approvedProperties'=> Property::query()->where('approval_status', ApprovalStatus::Approved->value)->count(),
            'pendingProperties' => Property::query()
                ->whereIn('approval_status', [ApprovalStatus::Submitted->value, ApprovalStatus::UnderReview->value])
                ->count(),
            'appointments'      => DB::table('appointments')->count(),
            'totalViews'        => (int) Property::query()->sum('view_count'),
        ];
    }

    /**
     * Monthly user registration counts for the last 12 months.
     * @return array{labels: array<int, string>, counts: array<int, int>}
     */
    public function monthlyRegistrations(int $monthsBack = 12): array
    {
        $start = now()->copy()->subMonths($monthsBack - 1)->startOfMonth();

        $rows = User::query()
            ->where('created_at', '>=', $start)
            ->get(['created_at'])
            ->groupBy(fn ($u) => $u->created_at->format('Y-m'))
            ->map->count();

        $labels = [];
        $counts = [];
        for ($i = 0; $i < $monthsBack; $i++) {
            $date = $start->copy()->addMonths($i);
            $key  = $date->format('Y-m');
            $labels[] = $date->format('M y');
            $counts[] = (int) ($rows[$key] ?? 0);
        }

        return ['labels' => $labels, 'counts' => $counts];
    }

    public function mostViewedProperties(int $limit = 8)
    {
        return Property::query()
            ->visible()
            ->orderByDesc('view_count')
            ->with('images')
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'city', 'view_count', 'price', 'type', 'status']);
    }

    public function propertyTypeBreakdown(): array
    {
        $rows = Property::query()
            ->visible()
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->all();

        $out = [];
        foreach (PropertyType::cases() as $case) {
            $out[$case->label()] = (int) ($rows[$case->value] ?? 0);
        }

        return $out;
    }
}
