<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Enums\AppointmentStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $donor = $user->donor;

        // Graceful redirect if donor profile is missing
        if (!$donor) {
            return redirect()->route('donor.profile.edit')
                ->with('warning', 'Please complete your donor profile to continue.');
        }

        $donor->load(['bloodType', 'donations', 'appointments']);

        $stats = [
            'total_donations'       => $donor->donations->where('status', 'successful')->count(),
            'upcoming_appointments' => $donor->appointments
                ->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Approved])
                ->where('appointment_date', '>=', now())
                ->count(),
            'last_donation'         => $donor->donations
                ->sortByDesc('donation_date')
                ->first()?->donation_date,
            'total_volume'          => $donor->donations
                ->where('status', 'successful')
                ->sum('volume'),
        ];

        $upcomingAppointments = $donor->appointments()
            ->where('appointment_date', '>=', now())
            ->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Approved])
            ->orderBy('appointment_date')
            ->take(3)
            ->get();

        $recentDonations = $donor->donations()
            ->with('bloodUnits')
            ->latest('donation_date')
            ->take(5)
            ->get();

        $recentNotifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        return view('donor.dashboard', compact(
            'donor',
            'stats',
            'upcomingAppointments',
            'recentDonations',
            'recentNotifications'
        ));
    }
}