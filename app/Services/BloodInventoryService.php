<?php

namespace App\Services;

use App\Enums\BloodUnitStatus;
use App\Enums\DonationStatus;
use App\Models\BloodUnit;
use App\Models\Donation;
use Carbon\Carbon;

class BloodInventoryService
{
    public function createUnitsFromDonation(Donation $donation): void
    {
        // Guard 1: only create units for successful donations
        if ($donation->status instanceof \App\Enums\DonationStatus) {
            if ($donation->status !== DonationStatus::Successful) return;
        } else {
            if ($donation->status !== 'successful') return;
        }

        // Guard 2: PREVENT DUPLICATES — check if a unit already exists for this donation
        $alreadyExists = BloodUnit::where('donation_id', $donation->donation_id)->exists();
        if ($alreadyExists) {
            return; // Unit already created, do not create another
        }

        // Guard 3: donor must have a blood type
        if (!$donation->donor->blood_type_id) {
            return;
        }

        BloodUnit::create([
            'donation_id'   => $donation->donation_id,
            'blood_type_id' => $donation->donor->blood_type_id,
            'stored_date'   => now(),
            'expiry_date'   => Carbon::now()->addDays(42),
            'status'        => BloodUnitStatus::Available,
        ]);
    }

    public function markExpiredUnits(): void
    {
        BloodUnit::where('status', BloodUnitStatus::Available)
            ->where('expiry_date', '<', now())
            ->update(['status' => BloodUnitStatus::Expired]);
    }

    public function getInventorySummary(): array
    {
        return BloodUnit::with('bloodType')
            ->where('status', BloodUnitStatus::Available)
            ->get()
            ->groupBy('bloodType.type_name')
            ->map(fn($units) => $units->count())
            ->toArray();
    }
}