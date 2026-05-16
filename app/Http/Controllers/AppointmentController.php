<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Property;
use App\Notifications\AppointmentBookedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $appointments = Appointment::query()
            ->where('buyer_id', $user->id)
            ->orWhere('agent_id', $user->id)
            ->with(['property.images', 'buyer', 'agent'])
            ->orderByDesc('scheduled_for')
            ->get();

        return view('appointment.index', compact('appointments'));
    }

    public function store(Request $request, Property $property): RedirectResponse
    {
        $user = $request->user();

        abort_if($user->id === $property->owner_id, 403, "Can't book a visit on your own listing.");

        $data = $request->validate([
            'scheduled_for' => ['required', 'date', 'after:now'],
            'notes'         => ['nullable', 'string', 'max:500'],
        ]);

        $appointment = Appointment::create([
            'property_id' => $property->id,
            'buyer_id'    => $user->id,
            'agent_id'    => $property->owner_id,
            'scheduled_for' => $data['scheduled_for'],
            'notes'       => $data['notes'] ?? null,
            'status'      => 'pending',
        ]);

        $property->owner?->notify(new AppointmentBookedNotification($appointment));

        return redirect()->route('appointments.index')->with('status', 'Visit requested. The agent will confirm soon.');
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless(
            $request->user()->id === $appointment->buyer_id || $request->user()->id === $appointment->agent_id,
            403
        );

        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', Appointment::STATUSES)],
        ]);

        $appointment->update($data);

        return back()->with('status', 'Appointment updated.');
    }
}
