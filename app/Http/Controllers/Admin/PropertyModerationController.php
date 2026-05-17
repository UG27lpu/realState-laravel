<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ApprovalStatus;
use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Notifications\PropertyDecisionNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyModerationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', ApprovalStatus::Submitted->value);

        $properties = Property::query()
            ->when($status, fn ($q) => $q->where('approval_status', $status))
            ->with(['owner', 'images'])
            ->latest()
            ->paginate(20);

        return view('admin.properties.index', [
            'properties' => $properties,
            'status'     => $status,
            'statuses'   => [
                ''                                  => 'All',
                ApprovalStatus::Submitted->value    => 'Submitted',
                ApprovalStatus::UnderReview->value  => 'Under review',
                ApprovalStatus::Approved->value     => 'Approved',
                ApprovalStatus::Rejected->value     => 'Rejected',
            ],
        ]);
    }

    public function review(Property $property): View
    {
        $property->load(['owner', 'images', 'documents']);

        return view('admin.properties.review', compact('property'));
    }

    public function approve(Request $request, Property $property): RedirectResponse
    {
        $property->update([
            'approval_status' => ApprovalStatus::Approved->value,
            'approved_at'     => now(),
            'rejected_at'     => null,
            'rejection_reason'=> null,
        ]);

        $property->owner?->notify(new PropertyDecisionNotification($property, 'approved'));

        return redirect()->route('admin.properties.index')->with('status', 'Property approved.');
    }

    public function reject(Request $request, Property $property): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $property->update([
            'approval_status' => ApprovalStatus::Rejected->value,
            'rejected_at'     => now(),
            'rejection_reason'=> $data['reason'],
        ]);

        $property->owner?->notify(new PropertyDecisionNotification($property, 'rejected', $data['reason']));

        return redirect()->route('admin.properties.index')->with('status', 'Property rejected.');
    }

    public function markUnderReview(Property $property): RedirectResponse
    {
        $property->update(['approval_status' => ApprovalStatus::UnderReview->value]);

        return back()->with('status', 'Marked under review.');
    }
}
