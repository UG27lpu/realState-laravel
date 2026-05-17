<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Property report — {{ $property->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #18181b; font-size: 12px; line-height: 1.5; }
        h1 { font-size: 22px; margin: 0; }
        h2 { font-size: 14px; margin: 16px 0 6px; padding-bottom: 4px; border-bottom: 1px solid #e4e4e7; }
        .muted { color: #71717a; font-size: 11px; }
        .row { display: flex; }
        .col { flex: 1; }
        .pill { display: inline-block; padding: 2px 8px; border-radius: 9999px; background: #eef2ff; color: #4338ca; font-size: 10px; }
        .demo-pill { background: #fff7ed; color: #c2410c; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        td { padding: 4px 0; vertical-align: top; }
        .grid { width: 100%; }
        .grid td { width: 50%; }
        .footer { margin-top: 24px; font-size: 10px; color: #71717a; border-top: 1px solid #e4e4e7; padding-top: 8px; }
    </style>
</head>
<body>
    <table style="margin-bottom: 8px">
        <tr>
            <td>
                <h1>{{ $property->title }}</h1>
                <p class="muted">{{ $property->address }}, {{ $property->city }} {{ $property->pincode }}</p>
                <p>
                    <span class="pill">{{ $property->type?->label() }}</span>
                    <span class="pill">{{ $property->status?->label() }}</span>
                    <span class="pill demo-pill">{{ $property->approval_status?->label() }}</span>
                </p>
            </td>
            <td style="text-align: right; width: 130px;">
                <img src="{{ $qrUrl }}" alt="QR" style="width:110px; height:110px;">
                <p class="muted" style="margin-top:4px;">Scan to verify</p>
            </td>
        </tr>
    </table>

    <h2>Listing details</h2>
    <table class="grid">
        <tr>
            <td><strong>Price:</strong> {{ config('estatify.currency.symbol') }}{{ number_format((float) $property->price) }}</td>
            <td><strong>Area:</strong> {{ $property->area ? number_format((float)$property->area).' '.$property->area_unit : '—' }}</td>
        </tr>
        <tr>
            <td><strong>Bedrooms:</strong> {{ $property->bedrooms ?? '—' }}</td>
            <td><strong>Bathrooms:</strong> {{ $property->bathrooms ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Floors:</strong> {{ $property->floors ?? '—' }}</td>
            <td><strong>Year built:</strong> {{ $property->year_built ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Furnished:</strong> {{ $property->furnished ? 'Yes' : 'No' }}</td>
            <td><strong>Parking:</strong> {{ $property->parking ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td><strong>Survey #:</strong> {{ $property->survey_number ?? '—' }}</td>
            <td><strong>Listed by:</strong> {{ $property->owner?->name }} ({{ $property->owner?->agency_name ?? 'Independent' }})</td>
        </tr>
    </table>

    <h2>About</h2>
    <p>{{ $property->description ?: '—' }}</p>

    @if (! empty($property->nearby_facilities))
        <h2>Nearby</h2>
        <p>{{ implode(', ', $property->nearby_facilities) }}</p>
    @endif

    <h2>Demo price estimate <span class="pill demo-pill">Demo only</span></h2>
    <p>
        Estimated value: <strong>{{ config('estatify.currency.symbol') }}{{ number_format($price['estimate']) }}</strong>
        (range {{ config('estatify.currency.symbol') }}{{ number_format($price['low']) }}
        – {{ config('estatify.currency.symbol') }}{{ number_format($price['high']) }})<br>
        Per {{ $property->area_unit }}: ~{{ config('estatify.currency.symbol') }}{{ number_format($price['per_sqft']) }}<br>
        <span class="muted">{{ $price['note'] }}</span>
    </p>

    <h2>Demo legal verification <span class="pill demo-pill">Simulated</span></h2>
    <p>
        Status: <strong>{{ app(\App\Services\Demo\LegalVerificationService::class)->statusLabel($legal['status']) }}</strong><br>
        @foreach ($legal['reasons'] as $reason)
            &bull; {{ $reason }}<br>
        @endforeach
    </p>

    <div class="footer">
        Report generated on {{ $generated }} by {{ config('app.name') }}. Verification URL: {{ $verifyUrl }}.
        Demo systems (AI, price prediction, legal verification, digital signature) are simulated and not legally authoritative.
    </div>
</body>
</html>
