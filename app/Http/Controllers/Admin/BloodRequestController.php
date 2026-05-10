<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodType;
use App\Enums\RequestStatus;
use App\Events\RequestStatusChanged;
use App\Services\RequestFulfillmentService;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    public function __construct(protected RequestFulfillmentService $fulfillmentService) {}

    public function index(Request $request)
{
    $bloodTypes = BloodType::all();

    $requests = BloodRequest::with(['hospital', 'bloodType'])
        ->when($request->search,     fn($q) => $q->whereHas('hospital', fn($q) =>
            $q->where('hospital_name', 'like', '%' . $request->search . '%')
        ))
        ->when($request->status,     fn($q) => $q->where('status', $request->status))
        ->when($request->blood_type, fn($q) => $q->where('blood_type_id', $request->blood_type))
        ->latest()
        ->paginate(15)
        ->withQueryString();

    return view('admin.blood-requests.index', compact('requests', 'bloodTypes'));
}

    public function show(BloodRequest $bloodRequest)
    {
        $bloodRequest->load(['hospital', 'bloodType', 'bloodUnits.bloodType']);
        return view('admin.blood-requests.show', compact('bloodRequest'));
    }

    public function approve(Request $request, BloodRequest $bloodRequest)
    {

        $request->validate([
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        if ($bloodRequest->status !== RequestStatus::Pending) {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $bloodRequest->update([
            'status'  => RequestStatus::Approved,
            'remarks' => $request->remarks,
        ]);

        $bloodRequest->hospital->user->notify(
    new \App\Notifications\BloodRequestApproved($bloodRequest->fresh())
);

        return back()->with('success', 'Blood request approved successfully.');
    }

    public function reject(Request $request, BloodRequest $bloodRequest)
    {

        $request->validate([
            'remarks' => ['required', 'string', 'max:500'],
        ]);

        if ($bloodRequest->status !== RequestStatus::Pending) {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $bloodRequest->update([
            'status'  => RequestStatus::Rejected,
            'remarks' => $request->remarks,
        ]);

        $bloodRequest->hospital->user->notify(
    new \App\Notifications\BloodRequestRejected($bloodRequest->fresh(), $request->remarks)
);

        return back()->with('success', 'Blood request rejected.');
    }

    public function fulfill(BloodRequest $bloodRequest)
    {

        $result = $this->fulfillmentService->fulfill($bloodRequest);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        $bloodRequest->hospital->user->notify(
    new \App\Notifications\BloodRequestFulfilled($bloodRequest->fresh(), $result['units_allocated'])
);

        return back()->with('success', "{$result['units_allocated']} unit(s) allocated successfully.");
    }
}