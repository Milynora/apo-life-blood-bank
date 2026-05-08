<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodType;
use App\Models\BloodUnit;
use App\Models\User;
use App\Enums\RequestStatus;
use App\Enums\BloodUnitStatus;
use App\Notifications\BloodRequestSubmitted;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = auth()->user()->hospital
            ->requests()
            ->with(['bloodType', 'bloodUnits'])
            ->when($request->status,     fn($q) => $q->where('status', $request->status))
            ->when($request->blood_type, fn($q) => $q->where('blood_type_id', $request->blood_type))
            ->when($request->date, fn($q) => $q->whereDate('request_date', $request->date))
            ->latest('request_date')
            ->latest('request_id')
            ->paginate(15)
            ->withQueryString();

        return view('hospital.requests.index', compact('requests'));
    }

    public function create()
    {
        $hospital   = auth()->user()->hospital;
        $bloodTypes = BloodType::orderBy('type_name')->get();
        $inventory  = BloodType::all()->mapWithKeys(function ($bt) {
            return [$bt->type_name => [
                'count' => BloodUnit::where('blood_type_id', $bt->blood_type_id)
                    ->where('status', 'available')->count()
            ]];
        });

        return view('hospital.requests.create', compact('hospital', 'bloodTypes', 'inventory'));
    }

    public function store(Request $request)
{
    $request->validate([
        'blood_type_id'    => ['required', 'exists:blood_types,blood_type_id'],
        'quantity'         => ['required', 'integer', 'min:1', 'max:100'],
        'urgency'          => ['required', 'in:routine,urgent,emergency'],
        'fulfillment'      => ['required', 'in:pickup,delivery'],
        'delivery_address' => ['required_if:fulfillment,delivery', 'nullable', 'string', 'max:500'],
        'remarks'          => ['nullable', 'string', 'max:500'],
        'needed_by' => ['nullable', 'date', 'after:today'],
    ]);

    $hospital = auth()->user()->hospital;

    // Block if hospital already has a pending request for the same blood type
$alreadyPending = BloodRequest::where('hospital_id', $hospital->hospital_id)
    ->where('blood_type_id', $request->blood_type_id)
    ->where('status', RequestStatus::Pending)
    ->exists();

if ($alreadyPending) {
    $bloodType = BloodType::find($request->blood_type_id);
    return back()
        ->withInput()
        ->with('error', 'You already have a pending request for ' . $bloodType->type_name . '. Please wait for it to be reviewed before submitting another.');
}

    $bloodRequest = BloodRequest::create([
        'hospital_id'      => $hospital->hospital_id,
        'blood_type_id'    => $request->blood_type_id,
        'quantity'         => $request->quantity,
        'urgency'          => $request->urgency,
        'fulfillment_type' => $request->fulfillment,
        'request_date'     => today(),
        'status'           => RequestStatus::Pending,
        'remarks'          => $request->remarks
            ? $request->remarks . ($request->delivery_address ? "\nDelivery address: " . $request->delivery_address : '')
            : ($request->delivery_address ? 'Delivery address: ' . $request->delivery_address : null),
        'needed_by' => $request->needed_by ?: null,
    ]);

    $staffAndAdmin = User::whereIn('role', ['staff', 'admin'])
        ->where('status', 'active')
        ->get();

    foreach ($staffAndAdmin as $user) {
        $user->notify(new BloodRequestSubmitted($bloodRequest));
    }

    return redirect()->route('hospital.requests.index')
        ->with('success', 'Blood request submitted successfully. Awaiting staff review.');
}

    public function show($id)
    {
        $hospital = auth()->user()->hospital;
        if (!$hospital) abort(403, 'No hospital account linked.');

        $bloodRequest = BloodRequest::where('hospital_id', $hospital->hospital_id)
            ->with(['bloodType', 'bloodUnits.bloodType'])
            ->findOrFail($id);

        $availableCount = BloodUnit::where('blood_type_id', $bloodRequest->blood_type_id)
            ->where('status', BloodUnitStatus::Available)
            ->where('expiry_date', '>=', now())
            ->count();

        $inventory = $this->getInventorySummary();

        return view('hospital.requests.show', compact('bloodRequest', 'availableCount', 'inventory'));
    }

    public function edit($id)
    {
        $hospital = auth()->user()->hospital;
        if (!$hospital) abort(403, 'No hospital account linked.');

        $bloodRequest = BloodRequest::where('hospital_id', $hospital->hospital_id)
            ->findOrFail($id);

        abort_if(
            $bloodRequest->status !== RequestStatus::Pending,
            403,
            'Only pending requests can be edited.'
        );

        $bloodTypes = BloodType::orderBy('type_name')->get();
        $inventory  = $this->getInventorySummary();

        return view('hospital.requests.edit', compact('bloodRequest', 'hospital', 'bloodTypes', 'inventory'));
    }

    public function update(Request $request, $id)
    {
        $hospital = auth()->user()->hospital;
        if (!$hospital) abort(403, 'No hospital account linked.');

        $bloodRequest = BloodRequest::where('hospital_id', $hospital->hospital_id)
            ->findOrFail($id);

        abort_if(
            $bloodRequest->status !== RequestStatus::Pending,
            403,
            'Only pending requests can be edited.'
        );

        $request->validate([
            'blood_type_id'    => ['required', 'exists:blood_types,blood_type_id'],
            'quantity'         => ['required', 'integer', 'min:1', 'max:100'],
            'urgency'          => ['required', 'in:routine,urgent,emergency'],
            'fulfillment'      => ['required', 'in:pickup,delivery'],
            'delivery_address' => ['required_if:fulfillment,delivery', 'nullable', 'string', 'max:500'],
            'remarks'          => ['nullable', 'string', 'max:500'],
            'needed_by' => ['nullable', 'date', 'after:today'],
        ]);

        $bloodRequest->update([
            'blood_type_id'    => $request->blood_type_id,
            'quantity'         => $request->quantity,
            'urgency'          => $request->urgency,
            'fulfillment_type' => $request->fulfillment,
            'remarks'          => $request->remarks
                ? $request->remarks . ($request->delivery_address ? "\nDelivery address: " . $request->delivery_address : '')
                : ($request->delivery_address ? 'Delivery address: ' . $request->delivery_address : null),
            'needed_by' => $request->needed_by ?: null,
        ]);

        return redirect()->route('hospital.requests.index')
            ->with('success', 'Request updated successfully.');
    }

    public function cancel($id)
{
    $hospital = auth()->user()->hospital;
    if (!$hospital) abort(403, 'No hospital account linked.');

    $bloodRequest = BloodRequest::where('hospital_id', $hospital->hospital_id)
        ->findOrFail($id);

    abort_if(
        $bloodRequest->status !== RequestStatus::Pending,
        403,
        'Only pending requests can be cancelled.'
    );

    $bloodRequest->update(['status' => RequestStatus::Cancelled]);

    return redirect()->route('hospital.requests.index')
        ->with('success', 'Blood request has been cancelled.');
}

    private function getInventorySummary(): array
    {
        $allTypes   = BloodType::orderBy('type_name')->get();
        $unitCounts = BloodUnit::where('status', BloodUnitStatus::Available)
            ->where('expiry_date', '>=', now())
            ->get()
            ->groupBy('blood_type_id')
            ->map(fn($units) => $units->count());

        return $allTypes->mapWithKeys(fn($bt) => [
            $bt->type_name => [
                'count'         => $unitCounts[$bt->blood_type_id] ?? 0,
                'blood_type_id' => $bt->blood_type_id,
            ]
        ])->toArray();
    }
}