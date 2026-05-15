<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Property comparison is stored in the session so visitors can shortlist
 * before signing in. A maximum of four entries keeps the comparison view
 * readable.
 */
class CompareController extends Controller
{
    private const SESSION_KEY = 'compare_property_ids';
    private const MAX_ITEMS   = 4;

    public function index(): View
    {
        $ids = session(self::SESSION_KEY, []);

        $properties = empty($ids)
            ? collect()
            : Property::query()->whereIn('id', $ids)->visible()->with(['images', 'owner'])->get();

        return view('compare.index', ['properties' => $properties]);
    }

    public function add(Request $request, Property $property): RedirectResponse
    {
        $ids = session(self::SESSION_KEY, []);

        if (! in_array($property->id, $ids, true)) {
            if (count($ids) >= self::MAX_ITEMS) {
                return back()->with('status', 'You can compare at most '.self::MAX_ITEMS.' properties at a time.');
            }
            $ids[] = $property->id;
            session([self::SESSION_KEY => $ids]);
        }

        return back()->with('status', 'Added to compare.');
    }

    public function remove(Request $request, Property $property): RedirectResponse
    {
        $ids = array_values(array_filter(session(self::SESSION_KEY, []), fn ($id) => $id !== $property->id));
        session([self::SESSION_KEY => $ids]);

        return back()->with('status', 'Removed from compare.');
    }

    public function clear(): RedirectResponse
    {
        session()->forget(self::SESSION_KEY);

        return back()->with('status', 'Compare cleared.');
    }
}
