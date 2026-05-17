<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Appointment receipt</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #18181b; font-size: 12px; line-height: 1.5; }
        h1 { font-size: 18px; margin: 0; }
        .muted { color: #71717a; font-size: 11px; }
        .box { border: 1px solid #e4e4e7; border-radius: 6px; padding: 12px; margin-top: 12px; }
        td { padding: 3px 0; }
    </style>
</head>
<body>
    <h1>Appointment receipt</h1>
    <p class="muted">Issued by {{ config('app.name') }} on {{ $generated }}</p>

    <div class="box">
        <table>
            <tr><td><strong>Booking #:</strong></td><td>EST-APT-{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}</td></tr>
            <tr><td><strong>Property:</strong></td><td>{{ $appointment->property?->title }} ({{ $appointment->property?->city }})</td></tr>
            <tr><td><strong>Visitor:</strong></td><td>{{ $appointment->buyer?->name }} &middot; {{ $appointment->buyer?->email }}</td></tr>
            <tr><td><strong>Agent:</strong></td><td>{{ $appointment->agent?->name }} ({{ $appointment->agent?->agency_name ?? 'Independent' }})</td></tr>
            <tr><td><strong>Date &amp; time:</strong></td><td>{{ $appointment->scheduled_for?->format('l, j F Y • g:i A') }}</td></tr>
            <tr><td><strong>Status:</strong></td><td>{{ ucfirst($appointment->status) }}</td></tr>
        </table>

        @if ($appointment->notes)
            <p class="muted" style="margin-top:8px;"><strong>Notes:</strong> {{ $appointment->notes }}</p>
        @endif
    </div>

    <p class="muted" style="margin-top: 18px;">
        This receipt is a record of the requested visit. Confirmation is subject to the agent's availability.
    </p>
</body>
</html>
