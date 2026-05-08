<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\{Donor, Screening, Appointment};
use App\Enums\EligibilityStatus;
use App\Enums\AppointmentStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScreeningController extends Controller
{
    public function index(Request $request)
    {
        $screenings = Screening::with(['donor.user', 'donor.bloodType', 'staff', 'appointment'])
            ->when($request->eligibility, fn($q) => $q->where('eligibility_status', $request->eligibility))
            ->when($request->date,        fn($q) => $q->whereDate('screening_date', $request->date))
            ->latest('screening_date')
            ->paginate(15)
            ->withQueryString();

            $stats = [
    'total'            => Screening::count(),
    'fit'              => Screening::where('eligibility_status', EligibilityStatus::Fit)->count(),
    'unfit'            => Screening::where('eligibility_status', EligibilityStatus::Unfit)->count(),
];

        return view('staff.screenings.index', compact('screenings', 'stats'));
    }

    public function create(Request $request)
    {
        // Appointments not yet screened
        $appointments = Appointment::with('donor.user')
            ->where('status', 'approved')
            ->whereDoesntHave('screening')
            ->get();

        // Only donors eligible for screening:
        // - No successful donation in the last 56 days
        $eligibleDonorIds = Donor::with(['user', 'bloodType'])
            ->get()
            ->filter(function ($donor) {
                $lastDonation = $donor->donations()
                    ->where('status', 'successful')
                    ->latest('donation_date')
                    ->first();

                if (!$lastDonation) return true; // Never donated — eligible

                $daysSince = (int) $lastDonation->donation_date->diffInDays(now());
                return $daysSince >= 56;
            });

        // All donors — for display purposes (ineligible shown as disabled)
        $allDonors = Donor::with(['user', 'bloodType'])->get();

        // Mark each donor with eligibility info
        $donors = $allDonors->map(function ($donor) {
            $lastDonation = $donor->donations()
                ->where('status', 'successful')
                ->latest('donation_date')
                ->first();

            if ($lastDonation) {
                $daysSince = (int) $lastDonation->donation_date->diffInDays(now());
                $donor->screening_eligible = $daysSince >= 56;
                $donor->days_since_donation = $daysSince;
                $donor->days_until_eligible = max(0, 56 - $daysSince);
            } else {
                $donor->screening_eligible  = true;
                $donor->days_since_donation = null;
                $donor->days_until_eligible = 0;
            }

            return $donor;
        });

        $selectedDonor       = $request->donor_id       ? Donor::with(['user','bloodType'])->find($request->donor_id) : null;
        $selectedAppointment = $request->appointment_id ? Appointment::find($request->appointment_id) : null;

        return view('staff.screenings.create', compact(
            'donors', 'appointments', 'selectedDonor', 'selectedAppointment'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'donor_id'         => ['required', 'exists:donors,donor_id'],
            'appointment_id'   => ['nullable', 'exists:appointments,appointment_id'],
            'date'             => ['required', 'date', 'before_or_equal:today'],
            'blood_pressure'   => ['nullable', 'string', 'max:20'],
            'hemoglobin_level' => ['nullable', 'numeric', 'min:0', 'max:30'],
            'weight'           => ['nullable', 'numeric', 'min:30', 'max:300'],
            'remarks'          => ['nullable', 'string', 'max:1000'],
        ]);

        $eligibility = 'fit';
        $reasons     = [];

        // Hemoglobin check
        if ($request->filled('hemoglobin_level') && $request->hemoglobin_level < 12.5) {
            $eligibility = 'unfit';
            $reasons[]   = 'Hemoglobin too low (' . $request->hemoglobin_level . ' g/dL, minimum 12.5)';
        }

        // Weight check
        if ($request->filled('weight') && $request->weight < 50) {
            $eligibility = 'unfit';
            $reasons[]   = 'Weight too low (' . $request->weight . ' kg, minimum 50 kg)';
        }

        // Blood pressure check
        if ($request->filled('blood_pressure')) {
            $parts = explode('/', str_replace(' ', '', $request->blood_pressure));
            if (count($parts) === 2) {
                $systolic  = (int) $parts[0];
                $diastolic = (int) $parts[1];
                if ($systolic > 160 || $systolic < 90 || $diastolic > 100 || $diastolic < 60) {
                    $eligibility = 'unfit';
                    $reasons[]   = 'Blood pressure out of range (' . $request->blood_pressure . ')';
                }
            }
        }

        // 56-day interval check — use (int) to get whole days
        $lastDonation = \App\Models\Donation::where('donor_id', $request->donor_id)
            ->where('status', 'successful')
            ->latest('donation_date')
            ->first();

        if ($lastDonation) {
            $daysSince = (int) $lastDonation->donation_date->diffInDays(now());
            if ($daysSince < 56) {
                $eligibility = 'unfit';
                $daysLeft    = 56 - $daysSince;
                $reasons[]   = "Last donation was only {$daysSince} day(s) ago (must wait {$daysLeft} more day(s))";
            }
        }

        // Build remarks
        $remarks = $request->remarks ?? '';
        if (!empty($reasons)) {
            $autoNote = '[System] Deferred reason(s): ' . implode('; ', $reasons);
            $remarks  = $remarks ? $remarks . "\n\n" . $autoNote : $autoNote;
        }

        $staffId = auth()->user()->staff?->staff_id
    ?? \App\Models\Staff::where('user_id', auth()->id())->value('staff_id');

// If admin has no staff record, create one on the fly
if (!$staffId && auth()->user()->isAdmin()) {
    $staffRecord = \App\Models\Staff::firstOrCreate(
        ['user_id' => auth()->id()]
    );
    $staffId = $staffRecord->staff_id;
}

$screening = Screening::create([
    'donor_id'           => $request->donor_id,
    'staff_id'           => $staffId,
            'appointment_id'     => $request->appointment_id ?: null,
            'blood_pressure'     => $request->blood_pressure,
            'hemoglobin_level'   => $request->hemoglobin_level,
            'weight'             => $request->weight,
            'eligibility_status' => $eligibility,
            'remarks'            => $remarks,
            'screening_date'     => $request->date,
        ]);

        $screening->donor->user->notify(
    new \App\Notifications\ScreeningRecorded($screening)
);

        // Auto-complete linked appointment
        if ($screening->appointment_id) {
            Appointment::where('appointment_id', $screening->appointment_id)
                ->where('status', AppointmentStatus::Approved)
                ->update(['status' => AppointmentStatus::Completed]);
        }

        $msg = $eligibility === 'fit'
            ? 'Screening saved. Donor is eligible — you can now record a donation.'
            : 'Screening saved. Donor is not eligible: ' . implode(', ', $reasons);

        return redirect()->route('staff.screenings.show', $screening)
            ->with($eligibility === 'fit' ? 'success' : 'error', $msg);
    }

    public function show(Screening $screening)
    {
        $screening->load(['donor.user', 'donor.bloodType', 'staff', 'appointment', 'donation.bloodUnits']);
        return view('staff.screenings.show', compact('screening'));
    }

    public function update(Request $request, Screening $screening)
{
    $request->validate([
        'date'             => ['required', 'date', 'before_or_equal:today'],
        'blood_pressure'   => ['nullable', 'string', 'max:20'],
        'hemoglobin_level' => ['nullable', 'numeric', 'min:0', 'max:30'],
        'weight'           => ['nullable', 'numeric', 'min:30', 'max:300'],
        'remarks'          => ['nullable', 'string', 'max:1000'],
    ]);

    $eligibility = 'fit';
    $reasons     = [];

    if ($request->filled('hemoglobin_level') && $request->hemoglobin_level < 12.5) {
        $eligibility = 'unfit';
        $reasons[]   = 'Hemoglobin too low (' . $request->hemoglobin_level . ' g/dL, minimum 12.5)';
    }

    if ($request->filled('weight') && $request->weight < 50) {
        $eligibility = 'unfit';
        $reasons[]   = 'Weight too low (' . $request->weight . ' kg, minimum 50 kg)';
    }

    if ($request->filled('blood_pressure')) {
        $parts = explode('/', str_replace(' ', '', $request->blood_pressure));
        if (count($parts) === 2) {
            $systolic  = (int) $parts[0];
            $diastolic = (int) $parts[1];
            if ($systolic > 160 || $systolic < 90 || $diastolic > 100 || $diastolic < 60) {
                $eligibility = 'unfit';
                $reasons[]   = 'Blood pressure out of range (' . $request->blood_pressure . ')';
            }
        }
    }

    $lastDonation = \App\Models\Donation::where('donor_id', $screening->donor_id)
        ->where('status', 'successful')
        ->latest('donation_date')
        ->first();

    if ($lastDonation) {
        $daysSince = (int) $lastDonation->donation_date->diffInDays(now());
        if ($daysSince < 56) {
            $eligibility = 'unfit';
            $daysLeft    = 56 - $daysSince;
            $reasons[]   = "Last donation was only {$daysSince} day(s) ago (must wait {$daysLeft} more day(s))";
        }
    }

    $remarks = $request->remarks ?? '';
    if (!empty($reasons)) {
        $autoNote = '[System] Deferred reason(s): ' . implode('; ', $reasons);
        $remarks  = $remarks ? $remarks . "\n\n" . $autoNote : $autoNote;
    }

    $screening->update([
        'screening_date'     => $request->date,
        'blood_pressure'     => $request->blood_pressure,
        'hemoglobin_level'   => $request->hemoglobin_level,
        'weight'             => $request->weight,
        'eligibility_status' => $eligibility,
        'remarks'            => $remarks,
    ]);

    return back()->with('success', 'Screening updated successfully.');
}
}