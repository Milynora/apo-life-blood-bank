<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;

class DonationHistoryController extends Controller
{
    public function index()
    {
        $donor = auth()->user()->donor;

        $donations = $donor->donations()
            ->with(['staff', 'bloodUnits.bloodType', 'screening'])
            ->latest('donation_date')
            ->paginate(10);

        $stats = [
            'total_donations' => $donor->donations()->where('status', 'successful')->count(),
            'total_volume'    => $donor->donations()->where('status', 'successful')->sum('volume'),
            'last_donation'   => $donor->donations()->latest('donation_date')->first()?->donation_date,
        ];

        return view('donor.donations.index', compact('donations', 'stats'));
    }
}