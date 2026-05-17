<?php

namespace App\Services;

use App\Models\Property;
use App\Services\Demo\LegalVerificationService;
use App\Services\Demo\PricePredictionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfService
{
    public function __construct(
        private PricePredictionService $pricing,
        private LegalVerificationService $legal,
    ) {}

    public function propertyReport(Property $property): Response
    {
        $property->load(['owner', 'images', 'documents']);

        $data = [
            'property'  => $property,
            'price'     => $this->pricing->forProperty($property),
            'legal'     => $this->legal->evaluate($property),
            'generated' => now()->format('d M Y, g:i A'),
            'qrUrl'     => route('verify.qr', $property),
            'verifyUrl' => route('verify.show', $property),
        ];

        return Pdf::loadView('pdf.property-report', $data)
            ->setPaper('a4')
            ->download('property-'.$property->slug.'.pdf');
    }
}
