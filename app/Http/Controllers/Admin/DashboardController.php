<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Hospital;
use App\Models\User;
use App\Enums\UserStatus;
use App\Enums\BloodUnitStatus;
use App\Enums\RequestStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_donors'     => Donor::count(),
            'total_hospitals'  => Hospital::count(),
            'total_donations'  => Donation::where('status', 'successful')->count(),
            'pending_users'    => User::where('status', UserStatus::Pending)->count(),
            'available_units'  => BloodUnit::where('status', BloodUnitStatus::Available)->count(),
            'pending_requests' => BloodRequest::where('status', RequestStatus::Pending)->count(),
            'total_requests'   => BloodRequest::count(),
            'expiring_soon'    => BloodUnit::where('status', BloodUnitStatus::Available)
                                     ->whereBetween('expiry_date', [now(), now()->addDays(7)])
                                     ->count(),
        ];

        $recentDonations = Donation::with(['donor.bloodType', 'donor.user', 'staff.user'])
            ->latest('donation_date')
            ->take(5)
            ->get();

        $pendingUsers = User::where('status', UserStatus::Pending)
            ->with(['donor', 'hospital'])
            ->latest()
            ->take(5)
            ->get();

        $recentRequests = BloodRequest::with(['hospital', 'bloodType'])
            ->latest()
            ->take(5)
            ->get();

        // All 8 blood types shown even if 0
        $allBloodTypes    = \App\Models\BloodType::orderBy('type_name')->get();
        $unitCounts       = BloodUnit::with('bloodType')
            ->where('status', BloodUnitStatus::Available)
            ->get()
            ->groupBy('bloodType.type_name')
            ->map(fn($units) => $units->count());

        $inventorySummary = $allBloodTypes->mapWithKeys(
            fn($bt) => [$bt->type_name => $unitCounts[$bt->type_name] ?? 0]
        );

        // Donations by month for the bar chart
        $donationsByMonth = Donation::selectRaw('MONTH(donation_date) as month, COUNT(*) as count')
            ->whereYear('donation_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        return view('admin.dashboard', compact(
            'stats',
            'recentDonations',
            'pendingUsers',
            'recentRequests',
            'inventorySummary',
            'donationsByMonth'
        ));
    }
}