<?php

namespace App\Http\Controllers;

use App\Actions\Property\StorePropertyAction;
use App\Actions\Property\UpdatePropertyAction;
use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Http\Requests\Property\PropertyRequest;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Services\PropertyMediaService;
use App\Services\PropertySearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Request $request, PropertySearchService $search): View
    {
        $properties = $search->paginate($request);

        return view('property.index', [
            'properties'    => $properties,
            'featured'      => Property::query()->visible()->featured()->with('images')->limit(6)->get(),
            'recentlyAdded' => Property::query()->visible()->with('images')->latest()->limit(8)->get(),
            'types'         => PropertyType::options(),
            'statuses'      => PropertyStatus::options(),
            'sorts'         => $search->sortOptions(),
            'filters'       => $request->only(['q', 'type', 'status', 'city', 'price_min', 'price_max', 'area_min', 'area_max', 'bedrooms', 'sort']),
        ]);
    }

    public function show(
        Property $property,
        \App\Services\Demo\PricePredictionService $pricing,
        \App\Services\Demo\RecommendationService $recommender,
    ): View {
        Gate::authorize('view', $property);

        $property->load(['images', 'videos', 'documents', 'owner']);
        $property->increment('view_count');

        $related = $recommender->similarTo($property, 4);

        return view('property.show', [
            'property'  => $property,
            'related'   => $related,
            'priceDemo' => $pricing->forProperty($property),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Property::class);

        return view('property.create', [
            'types'    => PropertyType::options(),
            'statuses' => PropertyStatus::options(),
        ]);
    }

    public function store(PropertyRequest $request, StorePropertyAction $action): RedirectResponse
    {
        $files = [
            'images'    => $request->file('images', []),
            'video'     => $request->file('video'),
            'documents' => $this->normaliseDocumentInput($request),
        ];

        $property = $action->execute($request->user()->id, $request->validated(), $files);

        return redirect()
            ->route('properties.show', $property)
            ->with('status', 'Property submitted for review.');
    }

    public function edit(Property $property): View
    {
        Gate::authorize('update', $property);

        return view('property.edit', [
            'property' => $property->load(['images', 'videos', 'documents']),
            'types'    => PropertyType::options(),
            'statuses' => PropertyStatus::options(),
        ]);
    }

    public function update(PropertyRequest $request, Property $property, UpdatePropertyAction $action): RedirectResponse
    {
        Gate::authorize('update', $property);

        $files = [
            'images'    => $request->file('images', []),
            'video'     => $request->file('video'),
            'documents' => $this->normaliseDocumentInput($request),
        ];

        $property = $action->execute($property, $request->validated(), $files);

        return redirect()
            ->route('properties.show', $property)
            ->with('status', 'Property updated.');
    }

    public function destroy(Property $property, PropertyMediaService $media): RedirectResponse
    {
        Gate::authorize('delete', $property);

        $media->deleteAllFor($property);
        $property->delete();

        return redirect()->route('properties.mine')->with('status', 'Property removed.');
    }

    public function mine(Request $request): View
    {
        $properties = Property::query()
            ->where('owner_id', $request->user()->id)
            ->with(['images'])
            ->latest()
            ->paginate(10);

        return view('property.mine', compact('properties'));
    }

    public function deleteImage(PropertyImage $image): RedirectResponse
    {
        $property = $image->property;
        Gate::authorize('update', $property);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('status', 'Image removed.');
    }

    private function normaliseDocumentInput(Request $request): array
    {
        $documents = $request->input('documents', []);
        $files     = $request->file('documents', []);
        $out       = [];

        foreach ($documents as $index => $row) {
            $file = $files[$index]['file'] ?? null;
            if (! $file) continue;
            $out[$index] = [
                'file'  => $file,
                'label' => $row['label'] ?? 'Document',
                'type'  => $row['type'] ?? 'other',
            ];
        }

        return $out;
    }
}
