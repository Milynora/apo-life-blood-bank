<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\{Donation, Donor, Screening};
use App\Events\DonationRecorded;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $donations = Donation::with(['donor.user', 'donor.bloodType', 'staff', 'screening'])
            ->when($request->search, fn($q) => $q->whereHas('donor.user', fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            ))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date,   fn($q) => $q->whereDate('donation_date', $request->date))
            ->latest('donation_date')
            ->paginate(15)
            ->withQueryString();

        $stats = [
    'total'      => \App\Models\Donation::count(),
    'successful' => \App\Models\Donation::where('status', \App\Enums\DonationStatus::Successful)->count(),
    'failed'     => \App\Models\Donation::where('status', \App\Enums\DonationStatus::Failed)->count(),
];

        return view('staff.donations.index', compact('donations', 'stats'));
    }

    public function create(Request $request)
    {
        // Only fit screenings without a donation yet
        $screenings = Screening::with(['donor.user', 'donor.bloodType', 'appointment'])
            ->where('eligibility_status', 'fit')
            ->doesntHave('donation')
            ->latest('screening_date')
            ->get();

        $donors = Donor::with(['user', 'bloodType'])->get();

        $selectedDonor = $request->donor_id
            ? Donor::with(['user', 'bloodType'])->find($request->donor_id)
            : null;

        // Auto-select screening for selected donor
        $selectedScreening = null;
        if ($request->donor_id) {
            $selectedScreening = $screenings->firstWhere('donor_id', $request->donor_id);
        }
        if ($request->screening_id) {
            $selectedScreening = Screening::with(['donor.user', 'donor.bloodType', 'appointment'])
                ->find($request->screening_id);
        }

        return view('staff.donations.create', compact(
            'donors', 'screenings', 'selectedDonor', 'selectedScreening'
        ));
    }

    public function store(Request $request)
{
    $request->validate([
        'donor_id'      => ['required', 'exists:donors,donor_id'],
        'screening_id'  => ['required', 'exists:screenings,screening_id'],
        'blood_type_id' => ['required', 'exists:blood_types,blood_type_id'],
        'donation_date' => ['required', 'date', 'before_or_equal:today'],
        'volume'        => ['required', 'numeric', 'min:200', 'max:550'],
        'status'        => ['required', 'in:successful,failed'],
        'remarks'       => [
            $request->status === 'failed' ? 'required' : 'nullable',
            'string', 'max:1000'
        ],
    ]);

    $screening = Screening::findOrFail($request->screening_id);

    if ($screening->donor_id != $request->donor_id) {
        return back()->with('error', 'Screening does not match the selected donor.');
    }
    if ($screening->eligibility_status->value !== 'fit') {
        return back()->with('error', 'Cannot record donation: donor was screened as UNFIT.');
    }
    if ($screening->donation()->exists()) {
        return back()->with('error', 'A donation has already been recorded for this screening.');
    }

    // Get or create staff record for admin
    $staffId = auth()->user()->staff?->staff_id
        ?? \App\Models\Staff::where('user_id', auth()->id())->value('staff_id');

    if (!$staffId && auth()->user()->isAdmin()) {
        $staffRecord = \App\Models\Staff::firstOrCreate(['user_id' => auth()->id()]);
        $staffId     = $staffRecord->staff_id;
    }

    // Load donor to update blood type
    $donor = Donor::findOrFail($request->donor_id);

    $donation = Donation::create([
        'donor_id'      => $request->donor_id,
        'staff_id'      => $staffId,
        'screening_id'  => $request->screening_id,
        'blood_type_id' => $request->blood_type_id,
        'donation_date' => $request->donation_date,
        'volume'        => $request->volume,
        'status'        => $request->status,
        'remarks'       => $request->remarks,
    ]);

    // Confirm and lock donor's blood type
    $donor->update(['blood_type_id' => $request->blood_type_id]);

    // Fire event — creates blood units via listener (only if successful)
if ($donation->status->value === 'successful') {
    event(new DonationRecorded($donation));
}

    // Send notification directly — single call, no duplicate
    $donation->donor->user->notify(
        new \App\Notifications\DonationRecorded($donation)
    );

    return redirect()->route('staff.donations.show', $donation)
        ->with('success', 'Donation recorded. Donor blood type confirmed as ' .
            \App\Models\BloodType::find($request->blood_type_id)->type_name . '.');
}

    public function show(Donation $donation)
    {
        $donation->load(['donor.user', 'donor.bloodType', 'staff', 'screening.appointment', 'bloodUnits.bloodType']);
        return view('staff.donations.show', compact('donation'));
    }

    public function update(Request $request, Donation $donation)
{
    $request->validate([
        'donation_date' => ['required', 'date', 'before_or_equal:today'],
        'volume'        => ['required', 'numeric', 'min:200', 'max:550'],
        'blood_type_id' => ['required', 'exists:blood_types,blood_type_id'],
        'status'        => ['required', 'in:successful,failed'],
        'remarks'       => [
            $request->status === 'failed' ? 'required' : 'nullable',
            'string', 'max:1000'
        ],
    ]);

    $donation->update([
        'donation_date' => $request->donation_date,
        'volume'        => $request->volume,
        'blood_type_id' => $request->blood_type_id,
        'status'        => $request->status,
        'remarks'       => $request->remarks,
    ]);

    // If changed to failed, remove any blood units
if ($request->status === 'failed') {
    $donation->bloodUnits()->delete();
}

// If changed to successful and no units exist yet, create them
if ($request->status === 'successful' && $donation->bloodUnits()->count() === 0) {
    event(new DonationRecorded($donation));
}

    return back()->with('success', 'Donation updated successfully.');
}
}