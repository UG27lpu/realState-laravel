<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\Demo\LegalVerificationService;
use Illuminate\Http\Response;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VerificationController extends Controller
{
    /**
     * Public verification page reached by scanning the property's QR code.
     * Shows registration status, demo legal verification, and demo digital
     * signature — every demo element is clearly tagged.
     */
    public function show(Property $property, LegalVerificationService $legal): View
    {
        $property->load(['owner', 'images', 'documents']);

        $legalResult = $legal->evaluate($property);
        $signature   = $this->signaturePayload($property);

        return view('verify.show', [
            'property'  => $property,
            'legal'     => $legalResult,
            'signature' => $signature,
        ]);
    }

    /**
     * SVG QR code for embedding on the property page / report PDF.
     */
    public function qr(Property $property): Response
    {
        $url = route('verify.show', $property);

        $svg = QrCode::format('svg')->size(220)->margin(1)->generate($url);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Demo digital signature — a deterministic visual stamp derived from the
     * property and owner identifiers. NOT a real cryptographic signature.
     */
    private function signaturePayload(Property $property): array
    {
        $payload = $property->id.'|'.$property->owner_id.'|'.$property->approved_at?->timestamp;
        $hash    = strtoupper(substr(hash('sha256', $payload), 0, 16));

        return [
            'reference' => 'ES-'.substr($hash, 0, 4).'-'.substr($hash, 4, 4).'-'.substr($hash, 8, 4).'-'.substr($hash, 12, 4),
            'issued_at' => $property->approved_at?->format('d M Y') ?? now()->format('d M Y'),
            'signed_by' => $property->owner?->name ?? 'Estatify Demo Authority',
            'note'      => 'Demonstration signature — not legally binding.',
        ];
    }
}
