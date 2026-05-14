<?php

namespace App\Actions\Property;

use App\Enums\ApprovalStatus;
use App\Models\Property;
use App\Services\PropertyMediaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class StorePropertyAction
{
    public function __construct(private PropertyMediaService $media) {}

    /**
     * Persist a new property along with its uploaded media and demo
     * documents. The owner becomes the currently authenticated user.
     */
    public function execute(int $ownerId, array $data, array $files = []): Property
    {
        return DB::transaction(function () use ($ownerId, $data, $files) {
            $property = new Property();
            $property->owner_id = $ownerId;
            $property->fill($data);
            $property->slug = Property::generateSlug($data['title']);
            $property->approval_status = ApprovalStatus::Submitted->value;
            $property->save();

            $this->media->syncFromUpload($property, $files);

            return $property->refresh();
        });
    }
}
