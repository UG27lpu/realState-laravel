<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $items = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->with(['property.images'])
            ->latest()
            ->get()
            ->map->property
            ->filter();

        return view('wishlist.index', ['items' => $items]);
    }

    public function toggle(Request $request, Property $property): RedirectResponse
    {
        $userId = $request->user()->id;

        $existing = Wishlist::where('user_id', $userId)
            ->where('property_id', $property->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Removed from your wishlist.';
        } else {
            Wishlist::create(['user_id' => $userId, 'property_id' => $property->id]);
            $message = 'Saved to your wishlist.';
        }

        return back()->with('status', $message);
    }
}
