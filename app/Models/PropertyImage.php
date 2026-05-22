<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyImage extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'path', 'caption', 'is_cover', 'sort_order'];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function url(): string
    {
        if (str_starts_with($this->path, 'https://images.unsplash.com/')) {
            $base = strtok($this->path, '?');
            return $base.'?auto=format&fit=crop&w=1200&q=80';
        }
        if (str_starts_with($this->path, 'http')) {
            return $this->path;
        }
        return asset('storage/'.$this->path);
    }

    public function thumbnailUrl(): string
    {
        if (str_starts_with($this->path, 'https://images.unsplash.com/')) {
            $base = strtok($this->path, '?');
            return $base.'?auto=format&fit=crop&w=500&q=75';
        }
        if (str_starts_with($this->path, 'http')) {
            return $this->path;
        }
        return asset('storage/'.$this->path);
    }
}
