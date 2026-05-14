<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PropertyMediaService
{
    private const DISK = 'public';

    /**
     * Persist all uploaded media (images, video, demo documents) attached
     * to a property submission.
     *
     * @param array{
     *     images?: array<int, UploadedFile>,
     *     video?: UploadedFile|null,
     *     documents?: array<int, array{file?: UploadedFile, label?: string, type?: string}>
     * } $files
     */
    public function syncFromUpload(Property $property, array $files, bool $replace = false): void
    {
        if ($replace) {
            foreach ($property->images as $img) {
                Storage::disk(self::DISK)->delete($img->path);
                $img->delete();
            }
            foreach ($property->videos as $v) {
                Storage::disk(self::DISK)->delete($v->path);
                $v->delete();
            }
        }

        $images = $files['images'] ?? [];
        if (is_array($images)) {
            $existingCount = $property->images()->count();
            foreach (array_values($images) as $index => $image) {
                if (! $image instanceof UploadedFile) continue;

                $path = $image->store("properties/{$property->id}/images", self::DISK);
                $property->images()->create([
                    'path'       => $path,
                    'caption'    => null,
                    'is_cover'   => ($existingCount + $index) === 0,
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }

        if (! empty($files['video']) && $files['video'] instanceof UploadedFile) {
            $videoPath = $files['video']->store("properties/{$property->id}/videos", self::DISK);
            $property->videos()->create([
                'path'    => $videoPath,
                'caption' => null,
            ]);
        }

        $documents = $files['documents'] ?? [];
        if (is_array($documents)) {
            foreach ($documents as $doc) {
                $file = $doc['file'] ?? null;
                if (! $file instanceof UploadedFile) continue;

                $docPath = $file->store("properties/{$property->id}/documents", self::DISK);
                $property->documents()->create([
                    'label'   => $doc['label'] ?? 'Document',
                    'type'    => $doc['type'] ?? 'other',
                    'path'    => $docPath,
                    'is_demo' => true,
                ]);
            }
        }
    }

    public function deleteAllFor(Property $property): void
    {
        Storage::disk(self::DISK)->deleteDirectory("properties/{$property->id}");
    }
}
