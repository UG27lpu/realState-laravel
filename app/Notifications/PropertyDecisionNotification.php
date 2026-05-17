<?php

namespace App\Notifications;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PropertyDecisionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Property $property,
        public string $decision,
        public ?string $reason = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'approval',
            'property_id'    => $this->property->id,
            'property_title' => $this->property->title,
            'decision'       => $this->decision,
            'reason'         => $this->reason,
            'url'            => route('properties.show', $this->property),
        ];
    }
}
