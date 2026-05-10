<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Donor;
use App\Enums\AppointmentStatus;
use App\Notifications\AppointmentApproved;
use App\Notifications\AppointmentRejected;
use App\Notifications\AppointmentCancelled;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AppointmentController extends Controller
{
    public function index(Request $request)
{
    Appointment::whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Approved])
        ->where('appointment_date', '<', now()->subHours(4))
        ->whereDoesntHave('screening')
        ->update(['status' => AppointmentStatus::NoShow]);

    $appointments = Appointment::with(['donor.user', 'donor.bloodType'])
        ->when($request->search, fn($q) => $q->whereHas('donor.user', fn($q) =>
            $q->where('name', 'like', '%' . $request->search . '%')
        ))
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->when($request->date,   fn($q) => $q->whereDate('appointment_date', $request->date))
        ->latest('appointment_date')
        ->paginate(15)
        ->withQueryString();

    $stats = [
        'pending'   => Appointment::where('status', AppointmentStatus::Pending)->count(),
        'approved'  => Appointment::where('status', AppointmentStatus::Approved)->count(),
        'completed' => Appointment::where('status', AppointmentStatus::Completed)->count(),
        'cancelled' => Appointment::where('status', AppointmentStatus::Cancelled)->count(),
        'rejected'  => Appointment::where('status', AppointmentStatus::Rejected)->count(),
        'no_show'   => Appointment::where('status', AppointmentStatus::NoShow)->count(),
    ];

    return view('staff.appointments.index', compact('appointments', 'stats'));
}

    public function show(Appointment $appointment)
    {
        $appointment->load(['donor.user', 'donor.bloodType', 'screening.donation']);
        return view('staff.appointments.show', compact('appointment'));
    }

    public function create(Request $request)
    {
        $donors = Donor::with(['user', 'bloodType'])
    ->whereHas('user', fn($q) => $q->where('status', 'active'))
    ->join('users', 'donors.user_id', '=', 'users.id')
    ->orderBy('users.name')
    ->select('donors.*')
    ->get();

        $selectedDonor = $request->donor_id
            ? Donor::with(['user', 'bloodType'])->find($request->donor_id)
            : null;

        return view('staff.appointments.create', compact('donors', 'selectedDonor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'donor_id'         => ['required', 'exists:donors,donor_id'],
            'appointment_date' => ['required', 'date', 'after:now'],
            'notes'            => ['nullable', 'string', 'max:500'],
        ]);

        $existing = Appointment::where('donor_id', $request->donor_id)
            ->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Approved])
            ->where('appointment_date', '>=', now())
            ->exists();

        if ($existing) {
            return back()->with('error', 'This donor already has a pending or upcoming appointment.');
        }

        $appointment = Appointment::create([
            'donor_id'         => $request->donor_id,
            'appointment_date' => $request->appointment_date,
            'status'           => AppointmentStatus::Approved,
            'notes'            => $request->notes,
        ]);

        $appointment->donor->user->notify(new AppointmentApproved($appointment));

        return redirect()->route('staff.appointments.index')
            ->with('success', 'Appointment created and automatically approved.');
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $request->validate([
            'status' => ['required', 'in:approved,rejected,cancelled'],
            'notes'  => ['nullable', 'string', 'max:500'],
        ]);

        // Pending → can be approved or rejected only
        if ($appointment->status === AppointmentStatus::Pending) {
            if (!in_array($request->status, ['approved', 'rejected'])) {
                return back()->with('error', 'Pending appointments can only be approved or rejected.');
            }
        }

        // Approved → can only be cancelled
        if ($appointment->status === AppointmentStatus::Approved) {
            if ($request->status !== 'cancelled') {
                return back()->with('error', 'Approved appointments can only be cancelled.');
            }
        }

        // Any other status — no updates allowed
        if (!in_array($appointment->status->value, ['pending', 'approved'])) {
            return back()->with('error', 'This appointment can no longer be updated.');
        }

        $appointment->update([
            'status' => AppointmentStatus::from($request->status),
            'notes'  => $request->notes,
        ]);

        $donorUser = $appointment->donor->user;

if ($request->status === 'approved') {
    $donorUser->notify(new AppointmentApproved($appointment));
} elseif ($request->status === 'rejected') {
    $donorUser->notify(new AppointmentRejected($appointment, $request->notes));
} elseif ($request->status === 'cancelled') {
    $donorUser->notify(new AppointmentCancelled($appointment, $request->notes));
}

        return back()->with('success', 'Appointment ' . $request->status . ' successfully.');
    }
}