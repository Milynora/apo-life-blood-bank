<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Enums\BloodUnitStatus;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->period ?? 'monthly';

        // Load donor.bloodType so blood type column works in blade
        $donations = Donation::with(['donor.bloodType', 'donor.user', 'staff'])
            ->when($period === 'monthly', fn($q) => $q->whereMonth('donation_date', now()->month)
                                                       ->whereYear('donation_date', now()->year))
            ->when($period === 'yearly',  fn($q) => $q->whereYear('donation_date', now()->year))
            ->latest('donation_date')
            ->get();

        $requests = BloodRequest::with(['hospital', 'bloodType'])
            ->when($period === 'monthly', fn($q) => $q->whereMonth('request_date', now()->month)
                                                       ->whereYear('request_date', now()->year))
            ->when($period === 'yearly',  fn($q) => $q->whereYear('request_date', now()->year))
            ->latest('request_date')
            ->get();

        // All blood types shown even if 0
        $allBloodTypes   = \App\Models\BloodType::orderBy('type_name')->pluck('type_name');
        $unitCounts      = BloodUnit::with('bloodType')
            ->where('status', BloodUnitStatus::Available)
            ->get()
            ->groupBy('bloodType.type_name')
            ->map(fn($units) => $units->count());

        $inventoryByType = $allBloodTypes->mapWithKeys(
            fn($type) => [$type => $unitCounts[$type] ?? 0]
        );

        $expiredUnits = BloodUnit::where('status', BloodUnitStatus::Expired)->count();

        $donationsByMonth = Donation::selectRaw('MONTH(donation_date) as month, COUNT(*) as count')
            ->whereYear('donation_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        return view('admin.reports.index', compact(
            'donations',
            'requests',
            'inventoryByType',
            'expiredUnits',
            'donationsByMonth',
            'period'
        ));
    }
}