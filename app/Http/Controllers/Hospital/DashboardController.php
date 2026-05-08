<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Enums\BloodUnitStatus;
use App\Enums\RequestStatus;
use App\Models\BloodUnit;

class DashboardController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $hospital = $user->hospital;

        // Graceful redirect if hospital profile is missing
        if (!$hospital) {
            return redirect()->route('hospital.profile.edit')
                ->with('warning', 'Please complete your hospital profile to continue.');
        }

        $stats = [
            'total_requests'     => $hospital->requests()->count(),
            'pending_requests'   => $hospital->requests()->where('status', RequestStatus::Pending)->count(),
            'approved_requests'  => $hospital->requests()->where('status', RequestStatus::Approved)->count(),
            'fulfilled_requests' => $hospital->requests()->where('status', RequestStatus::Fulfilled)->count(),
        ];

        $recentRequests = $hospital->requests()
            ->with('bloodType')
            ->latest()
            ->take(5)
            ->get();

        $inventorySummary = BloodUnit::with('bloodType')
            ->where('status', BloodUnitStatus::Available)
            ->get()
            ->groupBy('bloodType.type_name')
            ->map(fn($units) => $units->count());

        $recentNotifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        return view('hospital.dashboard', compact(
            'hospital',
            'stats',
            'recentRequests',
            'inventorySummary',
            'recentNotifications'
        ));
    }
}