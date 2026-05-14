<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyVideo extends Model
{
    protected $fillable = ['property_id', 'path', 'thumbnail_path', 'caption'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function url(): string
    {
        return asset('storage/'.$this->path);
    }
}
