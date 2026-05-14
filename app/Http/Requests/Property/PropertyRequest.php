<?php

namespace App\Http\Requests\Property;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->isAgent() || $user->isAdmin());
    }

    public function rules(): array
    {
        $maxImageKb = (int) config('estatify.media.max_image_size_kb', 4096);
        $maxVideoKb = (int) config('estatify.media.max_video_size_kb', 51200);
        $maxImages  = (int) config('estatify.media.max_property_images', 20);

        return [
            'title'        => ['required', 'string', 'max:160'],
            'description'  => ['nullable', 'string', 'max:5000'],
            'type'         => ['required', Rule::in(array_keys(PropertyType::options()))],
            'status'       => ['required', Rule::in(array_keys(PropertyStatus::options()))],

            'price'        => ['required', 'numeric', 'min:0', 'max:999999999999'],
            'area'         => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'area_unit'    => ['nullable', 'string', 'in:sqft,sqm,acre'],

            'bedrooms'     => ['nullable', 'integer', 'min:0', 'max:50'],
            'bathrooms'    => ['nullable', 'integer', 'min:0', 'max:50'],
            'floors'       => ['nullable', 'integer', 'min:0', 'max:200'],
            'year_built'   => ['nullable', 'integer', 'min:1800', 'max:'.(date('Y') + 1)],
            'furnished'    => ['sometimes', 'boolean'],
            'parking'      => ['sometimes', 'boolean'],

            'address'      => ['required', 'string', 'max:255'],
            'city'         => ['required', 'string', 'max:96'],
            'state'        => ['nullable', 'string', 'max:96'],
            'pincode'      => ['nullable', 'string', 'max:16'],
            'country'      => ['nullable', 'string', 'max:64'],

            'latitude'     => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'    => ['nullable', 'numeric', 'between:-180,180'],

            'survey_number'=> ['nullable', 'string', 'max:64'],
            'nearby_facilities' => ['nullable', 'array'],
            'nearby_facilities.*' => ['nullable', 'string', 'max:80'],

            'images'       => ['nullable', 'array', 'max:'.$maxImages],
            'images.*'     => ['file', 'image', 'max:'.$maxImageKb],

            'video'        => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:'.$maxVideoKb],

            'documents'    => ['nullable', 'array', 'max:8'],
            'documents.*.file'  => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:'.$maxImageKb],
            'documents.*.label' => ['required_with:documents.*.file', 'string', 'max:120'],
            'documents.*.type'  => ['nullable', 'string', 'in:deed,tax,noc,plan,other'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'furnished' => $this->boolean('furnished'),
            'parking'   => $this->boolean('parking'),
        ]);
    }
}
