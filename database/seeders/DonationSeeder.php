<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\Screening;
use App\Models\Staff;
use App\Models\BloodUnit;
use App\Enums\DonationStatus;
use App\Enums\BloodUnitStatus;
use App\Enums\EligibilityStatus;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $staffMembers = Staff::all();
        $staffList    = [$staffMembers[0], $staffMembers[1], $staffMembers[2]];

        // All fit screenings linked to appointments, ordered by date
        $fitScreenings = Screening::where('eligibility_status', EligibilityStatus::Fit)
            ->whereNotNull('appointment_id')
            ->with('donor.user', 'donor.bloodType')
            ->orderBy('screening_date')
            ->get()
            ->keyBy(fn($s) => $s->donor->user->email . '|' . $s->screening_date->format('Y-m-d'));

        // email|date, volume, status, remarks, staff_index
        // Failed donations: carla 01-30, patrick 02-10
        // All others: successful
        $donationData = [
            // ── January ─────────────────────────────────────
            ['rico@email.com|2026-01-20',    450, DonationStatus::Successful, null,                                                                 0],
            ['sasha@email.com|2026-01-22',   450, DonationStatus::Successful, null,                                                                 1],
            ['marco@email.com|2026-01-24',   450, DonationStatus::Successful, null,                                                                 2],
            ['diane@email.com|2026-01-26',   450, DonationStatus::Successful, null,                                                                 0],
            ['felix@email.com|2026-01-28',   450, DonationStatus::Successful, null,                                                                 1],
            ['carla@email.com|2026-01-30',   350, DonationStatus::Failed,     'Donor felt dizzy mid-draw. Collection stopped early.',              2],

            // ── February ────────────────────────────────────
            ['nathan@email.com|2026-02-02',  450, DonationStatus::Successful, null,                                                                 0],
            ['owen@email.com|2026-02-06',    450, DonationStatus::Successful, null,                                                                 2],
            ['yvonne@email.com|2026-02-08',  450, DonationStatus::Successful, null,                                                                 0],
            ['patrick@email.com|2026-02-10', 400, DonationStatus::Failed,     'Needle dislodged during collection. Insufficient volume collected.', 1],
            ['elias@email.com|2026-02-14',   450, DonationStatus::Successful, null,                                                                 0],
            ['trina@email.com|2026-02-16',   450, DonationStatus::Successful, null,                                                                 1],
            ['leo@email.com|2026-02-18',     450, DonationStatus::Successful, null,                                                                 2],
            ['carla@email.com|2026-02-20',   450, DonationStatus::Successful, null,                                                                 0], // retry after failed
            ['bianca@email.com|2026-02-25',  450, DonationStatus::Successful, null,                                                                 1], // retry after unfit

            // ── March ───────────────────────────────────────
            ['patrick@email.com|2026-03-05', 450, DonationStatus::Successful, null,                                                                 2], // retry after failed
            ['hana@email.com|2026-03-10',    450, DonationStatus::Successful, null,                                                                 0], // retry after unfit
            ['rico@email.com|2026-03-17',    450, DonationStatus::Successful, null,                                                                 1], // 56d after 01-20 ✓
            ['sasha@email.com|2026-03-19',   450, DonationStatus::Successful, null,                                                                 2], // 56d after 01-22 ✓
            ['marco@email.com|2026-03-21',   450, DonationStatus::Successful, null,                                                                 0], // 56d after 01-24 ✓
            ['diane@email.com|2026-03-23',   450, DonationStatus::Successful, null,                                                                 1], // 56d after 01-26 ✓
            ['felix@email.com|2026-03-25',   450, DonationStatus::Successful, null,                                                                 2], // 56d after 01-28 ✓
            ['nathan@email.com|2026-03-30',  450, DonationStatus::Successful, null,                                                                 0], // 56d after 02-02 ✓

            // ── April ───────────────────────────────────────
            ['owen@email.com|2026-04-03',    450, DonationStatus::Successful, null,                                                                 1], // 56d after 02-06 ✓
            ['yvonne@email.com|2026-04-05',  450, DonationStatus::Successful, null,                                                                 2], // 56d after 02-08 ✓
            ['elias@email.com|2026-04-11',   450, DonationStatus::Successful, null,                                                                 0], // 56d after 02-14 ✓
            ['trina@email.com|2026-04-13',   450, DonationStatus::Successful, null,                                                                 1], // 56d after 02-16 ✓
            ['leo@email.com|2026-04-15',     450, DonationStatus::Successful, null,                                                                 2], // 56d after 02-18 ✓
            ['carla@email.com|2026-04-17',   450, DonationStatus::Successful, null,                                                                 0], // 56d after 02-20 ✓
            ['bianca@email.com|2026-04-22',  450, DonationStatus::Successful, null,                                                                 1], // 56d after 02-25 ✓
            ['patrick@email.com|2026-04-30', 450, DonationStatus::Successful, null,                                                                 2], // 56d after 03-05 ✓

            // ── May ─────────────────────────────────────────
            ['hana@email.com|2026-05-05',    450, DonationStatus::Successful, null,                                                                 0], // 56d after 03-10 ✓
        ];

        foreach ($donationData as [$key, $volume, $status, $remarks, $staffIdx]) {
            $screening = $fitScreenings[$key] ?? null;
            if (!$screening) continue;

            $donor        = $screening->donor;
            $donationDate = $screening->screening_date;

            $donation = Donation::create([
                'donor_id'      => $donor->donor_id,
                'staff_id'      => $staffList[$staffIdx]->staff_id,
                'screening_id'  => $screening->screening_id,
                'blood_type_id' => $donor->blood_type_id,
                'donation_date' => $donationDate,
                'volume'        => $volume,
                'status'        => $status,
                'remarks'       => $remarks,
                'created_at'    => $donationDate,
                'updated_at'    => $donationDate,
            ]);

            if ($status === DonationStatus::Successful) {
                BloodUnit::create([
                    'donation_id'   => $donation->donation_id,
                    'blood_type_id' => $donor->blood_type_id,
                    'stored_date'   => $donationDate,
                    'expiry_date'   => $donationDate->copy()->addDays(42),
                    'status'        => BloodUnitStatus::Available,
                    'created_at'    => $donationDate,
                    'updated_at'    => $donationDate,
                ]);
            }
        }
    }
}