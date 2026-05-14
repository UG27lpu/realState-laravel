<?php

namespace App\Actions\Property;

use App\Models\Property;
use App\Services\PropertyMediaService;
use Illuminate\Support\Facades\DB;

class UpdatePropertyAction
{
    public function __construct(private PropertyMediaService $media) {}

    public function execute(Property $property, array $data, array $files = []): Property
    {
        return DB::transaction(function () use ($property, $data, $files) {
            $property->fill($data);

            if (! empty($data['title']) && $data['title'] !== $property->getOriginal('title')) {
                $property->slug = Property::generateSlug($data['title']);
            }

            $property->save();

            $this->media->syncFromUpload($property, $files, replace: false);

            return $property->refresh();
        });
    }
}
