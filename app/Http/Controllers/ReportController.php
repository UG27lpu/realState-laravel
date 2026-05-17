<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Property;
use App\Services\PdfService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function propertyReport(Property $property, PdfService $pdf): Response
    {
        Gate::authorize('view', $property);

        return $pdf->propertyReport($property);
    }

    public function appointmentReceipt(Appointment $appointment, PdfService $pdf): Response
    {
        abort_unless(
            auth()->id() === $appointment->buyer_id || auth()->id() === $appointment->agent_id || auth()->user()?->isAdmin(),
            403
        );

        return $pdf->appointmentReceipt($appointment);
    }
}
