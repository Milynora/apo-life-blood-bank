<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = auth()->user()->donor
            ->appointments()
            ->latest('appointment_date')
            ->paginate(10);

        return view('donor.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $donor = auth()->user()->donor->load('bloodType');
        return view('donor.appointments.create', compact('donor'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $request->validate([
            'appointment_date' => [
                'required',
                'date',
                'after:now',
                'before:' . now()->addMonths(3)->toDateString(),
            ],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $donor = auth()->user()->donor;

        $existing = $donor->appointments()
            ->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Approved])
            ->where('appointment_date', '>=', now())
            ->exists();

        if ($existing) {
            return back()->with('error', 'You already have a pending or approved upcoming appointment.');
        }

        $appointment = Appointment::create([
            'donor_id'         => $donor->donor_id,
            'appointment_date' => $request->appointment_date,
            'status'           => AppointmentStatus::Pending,
            'notes'            => $request->notes,
        ]);

        // Notify all staff and admin
        $staffAndAdmin = \App\Models\User::whereIn('role', ['staff', 'admin'])
            ->where('status', 'active')
            ->get();

        foreach ($staffAndAdmin as $user) {
            $user->notify(new \App\Notifications\AppointmentRequested($appointment));
        }

        return redirect()->route('donor.appointments.index')
            ->with('success', 'Appointment scheduled successfully. Please wait for approval.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);

        $appointment->load(['donor.user', 'donor.bloodType', 'screening', 'donation']);

        return view('donor.appointments.show', compact('appointment'));
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('cancel', $appointment);

        if (!in_array($appointment->status, [AppointmentStatus::Pending, AppointmentStatus::Approved])) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointment->update(['status' => AppointmentStatus::Cancelled]);

        return back()->with('success', 'Appointment cancelled successfully.');
    }
}