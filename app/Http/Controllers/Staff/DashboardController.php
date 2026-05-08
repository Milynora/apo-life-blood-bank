<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BloodUnit;
use App\Models\Donation;
use App\Models\Donor;
use App\Enums\AppointmentStatus;
use App\Enums\BloodUnitStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_donors'       => Donor::count(),
            'today_donations'    => Donation::whereDate('donation_date', today())->count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'available_units'    => BloodUnit::where('status', BloodUnitStatus::Available)->count(),
            'expiring_soon'      => BloodUnit::where('status', BloodUnitStatus::Available)
                                       ->whereBetween('expiry_date', [now(), now()->addDays(7)])
                                       ->count(),
        ];

        $todayAppointments = Appointment::with('donor')
            ->whereDate('appointment_date', today())
            ->where('status', AppointmentStatus::Approved)
            ->orderBy('appointment_date')
            ->get();

        $recentDonations = Donation::with(['donor', 'staff'])
            ->latest()
            ->take(5)
            ->get();

        $allBloodTypes = \App\Models\BloodType::orderBy('type_name')->pluck('type_name');
$unitCounts    = BloodUnit::with('bloodType')
    ->where('status', BloodUnitStatus::Available)
    ->get()
    ->groupBy('bloodType.type_name')
    ->map(fn($units) => $units->count());

$inventorySummary = $allBloodTypes->mapWithKeys(
    fn($type) => [$type => $unitCounts[$type] ?? 0]
);

        return view('staff.dashboard', compact(
            'stats',
            'todayAppointments',
            'recentDonations',
            'inventorySummary'
        ));
    }
}