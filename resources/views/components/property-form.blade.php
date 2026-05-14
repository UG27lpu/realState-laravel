@props([
    'property' => null,
    'types' => [],
    'statuses' => [],
    'submitLabel' => 'Save property',
    'action' => null,
    'method' => 'POST',
])

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-8"
      x-data="{ facilities: @js(old('nearby_facilities', $property?->nearby_facilities ?? [])) }">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    <section class="space-y-4">
        <h2 class="text-lg font-semibold">Basics</h2>
        <x-input label="Title" name="title" :value="$property?->title" required />
        <x-textarea label="Description" name="description" :value="$property?->description" rows="5" />
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-select label="Property type" name="type" :options="$types" :value="$property?->type?->value" placeholder="Select a type" />
            <x-select label="Listing status" name="status" :options="$statuses" :value="$property?->status?->value" placeholder="Select a status" />
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-input label="Price ({{ config('estatify.currency.code') }})" name="price" type="number" step="0.01" :value="$property?->price" required />
            <x-input label="Area" name="area" type="number" step="0.01" :value="$property?->area" />
            <x-select label="Area unit" name="area_unit" :options="['sqft' => 'Square feet', 'sqm' => 'Square metres', 'acre' => 'Acres']" :value="$property?->area_unit ?? 'sqft'" />
        </div>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <x-input label="Bedrooms" name="bedrooms" type="number" min="0" :value="$property?->bedrooms" />
            <x-input label="Bathrooms" name="bathrooms" type="number" min="0" :value="$property?->bathrooms" />
            <x-input label="Floors" name="floors" type="number" min="0" :value="$property?->floors" />
            <x-input label="Year built" name="year_built" type="number" :value="$property?->year_built" />
        </div>
        <div class="flex flex-wrap gap-4 text-sm">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="furnished" value="1" {{ $property?->furnished ? 'checked' : '' }}> Furnished
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="parking" value="1" {{ $property?->parking ? 'checked' : '' }}> Parking
            </label>
        </div>
    </section>

    <section class="space-y-4">
        <h2 class="text-lg font-semibold">Location</h2>
        <x-input label="Address" name="address" :value="$property?->address" required />
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-input label="City" name="city" :value="$property?->city" required />
            <x-input label="State" name="state" :value="$property?->state" />
            <x-input label="Pincode" name="pincode" :value="$property?->pincode" />
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-input label="Country" name="country" :value="$property?->country ?? 'India'" />
            <x-input label="Latitude" name="latitude" type="number" step="0.0000001" :value="$property?->latitude" />
            <x-input label="Longitude" name="longitude" type="number" step="0.0000001" :value="$property?->longitude" />
        </div>
        <x-input label="Survey number" name="survey_number" :value="$property?->survey_number" />

        <div>
            <span class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nearby facilities</span>
            <div class="flex flex-wrap gap-2" x-data="{ value: '' }">
                <template x-for="(f, i) in facilities" :key="i">
                    <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 px-3 py-1 text-xs dark:bg-zinc-800">
                        <span x-text="f"></span>
                        <input type="hidden" :name="'nearby_facilities[]'" :value="f">
                        <button type="button" class="text-zinc-400 hover:text-rose-500" @click="facilities.splice(i, 1)">&times;</button>
                    </span>
                </template>
                <input type="text" x-model="value" @keydown.enter.prevent="if(value){facilities.push(value); value=''}"
                       placeholder="School, park, metro…"
                       class="rounded-xl border border-dashed border-zinc-300 bg-transparent px-3 py-1 text-sm dark:border-zinc-700">
            </div>
        </div>
    </section>

    <section class="space-y-4">
        <h2 class="text-lg font-semibold">Media</h2>
        <div>
            <label class="block">
                <span class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Photos</span>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-3 file:py-2 file:text-white dark:file:bg-white dark:file:text-zinc-900">
            </label>
            @error('images.*')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror
        </div>
        <div>
            <label class="block">
                <span class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Walk-through video (optional)</span>
                <input type="file" name="video" accept="video/*"
                       class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-3 file:py-2 file:text-white dark:file:bg-white dark:file:text-zinc-900">
            </label>
            @error('video')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror
        </div>

        @if ($property && $property->images->isNotEmpty())
            <div>
                <p class="mb-2 text-sm font-medium">Existing photos</p>
                <div class="grid grid-cols-3 gap-2 sm:grid-cols-6">
                    @foreach ($property->images as $img)
                        <div class="relative">
                            <img src="{{ $img->url() }}" alt="" class="aspect-square w-full rounded-lg object-cover">
                            <form method="POST" action="{{ route('properties.images.destroy', $img) }}" class="absolute right-1 top-1">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-full bg-rose-600 px-1.5 text-[10px] text-white">&times;</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <section class="space-y-4" x-data="{ docs: [{ label: '', type: 'deed' }] }">
        <div class="flex items-center gap-2">
            <h2 class="text-lg font-semibold">Demo documents</h2>
            <x-demo-tag label="Sample only" />
        </div>
        <p class="text-xs text-zinc-500">Attach sample paperwork. These are clearly labelled as demo documents wherever they appear.</p>

        <template x-for="(doc, idx) in docs" :key="idx">
            <div class="grid grid-cols-1 gap-3 rounded-2xl border border-dashed border-zinc-300 p-3 dark:border-zinc-700 sm:grid-cols-3">
                <input type="text" :name="'documents['+idx+'][label]'" x-model="doc.label" placeholder="Document label (e.g. Title deed)" class="rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                <select :name="'documents['+idx+'][type]'" x-model="doc.type" class="rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <option value="deed">Title deed</option>
                    <option value="tax">Tax receipt</option>
                    <option value="noc">NOC</option>
                    <option value="plan">Floor plan</option>
                    <option value="other">Other</option>
                </select>
                <input type="file" :name="'documents['+idx+'][file]'" accept="application/pdf,image/*" class="text-sm">
            </div>
        </template>
        <x-button type="button" variant="outline" size="sm" @click="docs.push({ label: '', type: 'other' })">Add another document</x-button>
    </section>

    <div class="flex justify-end">
        <x-button type="submit">{{ $submitLabel }}</x-button>
    </div>
</form>
