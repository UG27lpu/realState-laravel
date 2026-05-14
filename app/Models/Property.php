<?php

namespace App\Models;

use App\Enums\ApprovalStatus;
use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id', 'title', 'slug', 'description',
        'type', 'status', 'approval_status',
        'price', 'area', 'area_unit',
        'bedrooms', 'bathrooms', 'floors', 'year_built', 'furnished', 'parking',
        'address', 'city', 'state', 'pincode', 'country',
        'latitude', 'longitude',
        'survey_number', 'legal_verification_status',
        'is_featured', 'view_count', 'nearby_facilities',
        'approved_at', 'rejected_at', 'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'type'              => PropertyType::class,
            'status'            => PropertyStatus::class,
            'approval_status'   => ApprovalStatus::class,
            'price'             => 'decimal:2',
            'area'              => 'decimal:2',
            'latitude'          => 'decimal:7',
            'longitude'         => 'decimal:7',
            'furnished'         => 'boolean',
            'parking'           => 'boolean',
            'is_featured'       => 'boolean',
            'nearby_facilities' => 'array',
            'approved_at'       => 'datetime',
            'rejected_at'       => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(PropertyVideo::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function coverImage(): ?PropertyImage
    {
        return $this->images->firstWhere('is_cover', true) ?? $this->images->first();
    }

    public function coverUrl(): string
    {
        $cover = $this->coverImage();

        if ($cover) {
            return asset('storage/'.$cover->path);
        }

        return asset('images/placeholders/property-'.(($this->id ?? 0) % 4 + 1).'.svg');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeVisible(Builder $q): Builder
    {
        return $q->where('approval_status', ApprovalStatus::Approved->value);
    }

    public function scopeOfType(Builder $q, ?string $type): Builder
    {
        if (! $type) return $q;
        return $q->where('type', $type);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    public static function generateSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;
        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}
