<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(AnalyticsService $analytics): View
    {
        return view('admin.dashboard', [
            'summary'      => $analytics->summary(),
            'registrations'=> $analytics->monthlyRegistrations(12),
            'mostViewed'   => $analytics->mostViewedProperties(6),
            'byType'       => $analytics->propertyTypeBreakdown(),
        ]);
    }
}
