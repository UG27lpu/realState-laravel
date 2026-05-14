<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('dashboard.index', [
            'user'              => $user,
            'role'              => $user->getRoleNames()->first() ?? 'user',
            'wishlistCount'     => Schema::hasTable('wishlists') ? $user->wishlistedProperties()->count() : 0,
            'appointmentsCount' => Schema::hasTable('appointments') ? $user->appointments()->count() : 0,
            'propertiesCount'   => Schema::hasTable('properties') ? $user->properties()->count() : 0,
        ]);
    }
}
