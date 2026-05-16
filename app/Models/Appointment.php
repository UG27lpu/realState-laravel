<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'confirmed', 'cancelled', 'completed'];

    protected $fillable = ['property_id', 'buyer_id', 'agent_id', 'scheduled_for', 'status', 'notes'];

    protected $casts = [
        'scheduled_for' => 'datetime',
    ];

    public function property(): BelongsTo { return $this->belongsTo(Property::class); }
    public function buyer(): BelongsTo    { return $this->belongsTo(User::class, 'buyer_id'); }
    public function agent(): BelongsTo    { return $this->belongsTo(User::class, 'agent_id'); }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'pending'   => 'amber',
            'confirmed' => 'green',
            'completed' => 'indigo',
            'cancelled' => 'red',
            default     => 'zinc',
        };
    }
}
