<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyDocument extends Model
{
    protected $fillable = ['property_id', 'label', 'type', 'path', 'is_demo'];

    protected $casts = [
        'is_demo' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function url(): string
    {
        return asset('storage/'.$this->path);
    }

    /**
     * These uploads are demo / sample documents only. The flag drives the
     * "Demo only" tag the UI shows next to every entry.
     */
    public function isDemo(): bool
    {
        return $this->is_demo;
    }
}
