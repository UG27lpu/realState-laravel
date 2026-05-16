<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentBookedNotification extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'appointment',
            'appointment_id' => $this->appointment->id,
            'property_title' => $this->appointment->property?->title,
            'buyer'          => $this->appointment->buyer?->name,
            'scheduled_for'  => $this->appointment->scheduled_for?->toDateTimeString(),
            'url'            => route('appointments.index'),
        ];
    }
}
