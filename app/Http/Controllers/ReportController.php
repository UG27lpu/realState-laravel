<?php

namespace App\Http\Controllers;

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
}
