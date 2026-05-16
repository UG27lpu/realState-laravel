<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = ['property_id', 'buyer_id', 'agent_id', 'last_message_at'];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function property(): BelongsTo  { return $this->belongsTo(Property::class); }
    public function buyer(): BelongsTo     { return $this->belongsTo(User::class, 'buyer_id'); }
    public function agent(): BelongsTo     { return $this->belongsTo(User::class, 'agent_id'); }
    public function messages(): HasMany    { return $this->hasMany(Message::class)->oldest(); }

    public function counterpartFor(User $user): ?User
    {
        return $user->id === $this->buyer_id ? $this->agent : $this->buyer;
    }
}
