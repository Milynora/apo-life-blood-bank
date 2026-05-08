<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Screening;
use App\Models\Donor;
use App\Models\Appointment;
use App\Models\Staff;
use App\Enums\AppointmentStatus;
use App\Enums\EligibilityStatus;

class ScreeningSeeder extends Seeder
{
    public function run(): void
    {
        $staffMembers = Staff::all();
        $staffList    = [$staffMembers[0], $staffMembers[1], $staffMembers[2]];

        $donors = Donor::with('user')->get()->keyBy(fn($d) => $d->user->email);

        $completedAppointments = Appointment::where('status', AppointmentStatus::Completed)
            ->with('donor.user')
            ->orderBy('appointment_date')
            ->get();

        // Define screening results per donor per appointment date
        // email, appointment_date (Y-m-d), eligibility, BP, hemoglobin, weight, remarks, staff_index
        $screeningMap = [
            // ── Round 1: January ────────────────────
            ['rico@email.com',    '2026-01-20', EligibilityStatus::Fit,   '118/76',  14.8, 70.0, null,                                                                                                          0],
            ['sasha@email.com',   '2026-01-22', EligibilityStatus::Fit,   '116/74',  13.9, 56.0, null,                                                                                                          1],
            ['marco@email.com',   '2026-01-24', EligibilityStatus::Fit,   '120/80',  15.1, 74.0, null,                                                                                                          2],
            ['diane@email.com',   '2026-01-26', EligibilityStatus::Fit,   '114/72',  14.2, 60.0, null,                                                                                                          0],
            ['felix@email.com',   '2026-01-28', EligibilityStatus::Fit,   '122/82',  15.5, 80.0, null,                                                                                                          1],
            ['carla@email.com',   '2026-01-30', EligibilityStatus::Fit,   '110/70',  13.6, 52.0, null,                                                                                                          2],

            // ── Round 1: February ───────────────────
            ['nathan@email.com',  '2026-02-02', EligibilityStatus::Fit,   '124/84',  15.8, 85.0, null,                                                                                                          0],
            ['bianca@email.com',  '2026-02-04', EligibilityStatus::Unfit, '168/102', 11.0, 47.0, 'Deferred: Hemoglobin too low (11.0 g/dL, min 12.5); Weight too low (47.0 kg, min 50 kg); BP out of range.',  1],
            ['owen@email.com',    '2026-02-06', EligibilityStatus::Fit,   '119/78',  14.5, 72.0, null,                                                                                                          2],
            ['yvonne@email.com',  '2026-02-08', EligibilityStatus::Fit,   '115/75',  13.7, 58.0, null,                                                                                                          0],
            ['patrick@email.com', '2026-02-10', EligibilityStatus::Fit,   '121/81',  15.3, 78.0, null,                                                                                                          1],
            ['hana@email.com',    '2026-02-12', EligibilityStatus::Unfit, '172/108', 11.5, 49.0, 'Deferred: Blood pressure elevated (172/108); Hemoglobin below threshold (11.5 g/dL).',                       2],
            ['elias@email.com',   '2026-02-14', EligibilityStatus::Fit,   '117/77',  14.9, 76.0, null,                                                                                                          0],
            ['trina@email.com',   '2026-02-16', EligibilityStatus::Fit,   '113/73',  14.1, 62.0, null,                                                                                                          1],
            ['leo@email.com',     '2026-02-18', EligibilityStatus::Fit,   '120/80',  15.0, 82.0, null,                                                                                                          2],
            ['carla@email.com',   '2026-02-20', EligibilityStatus::Fit,   '112/72',  13.8, 53.0, null,                                                                                                          0],
            ['bianca@email.com',  '2026-02-25', EligibilityStatus::Fit,   '116/74',  13.2, 51.0, null,                                                                                                          1],

            // ── Round 2: March ──────────────────────
            ['patrick@email.com', '2026-03-05', EligibilityStatus::Fit,   '120/78',  15.1, 79.0, null,                                                                                                          2],
            ['hana@email.com',    '2026-03-10', EligibilityStatus::Fit,   '116/76',  13.5, 52.0, null,                                                                                                          0],
            ['rico@email.com',    '2026-03-17', EligibilityStatus::Fit,   '118/76',  14.7, 71.0, null,                                                                                                          1],
            ['sasha@email.com',   '2026-03-19', EligibilityStatus::Fit,   '115/73',  13.8, 57.0, null,                                                                                                          2],
            ['marco@email.com',   '2026-03-21', EligibilityStatus::Fit,   '119/79',  15.0, 75.0, null,                                                                                                          0],
            ['diane@email.com',   '2026-03-23', EligibilityStatus::Fit,   '113/71',  14.3, 61.0, null,                                                                                                          1],
            ['felix@email.com',   '2026-03-25', EligibilityStatus::Fit,   '121/81',  15.4, 81.0, null,                                                                                                          2],
            ['nathan@email.com',  '2026-03-30', EligibilityStatus::Fit,   '123/83',  15.7, 86.0, null,                                                                                                          0],

            // ── Round 3: April ──────────────────────
            ['owen@email.com',    '2026-04-03', EligibilityStatus::Fit,   '118/77',  14.4, 73.0, null,                                                                                                          1],
            ['yvonne@email.com',  '2026-04-05', EligibilityStatus::Fit,   '114/74',  13.6, 59.0, null,                                                                                                          2],
            ['elias@email.com',   '2026-04-11', EligibilityStatus::Fit,   '116/76',  14.8, 77.0, null,                                                                                                          0],
            ['trina@email.com',   '2026-04-13', EligibilityStatus::Fit,   '112/72',  14.0, 63.0, null,                                                                                                          1],
            ['leo@email.com',     '2026-04-15', EligibilityStatus::Fit,   '119/79',  14.9, 83.0, null,                                                                                                          2],
            ['carla@email.com',   '2026-04-17', EligibilityStatus::Fit,   '111/71',  13.9, 54.0, null,                                                                                                          0],
            ['bianca@email.com',  '2026-04-22', EligibilityStatus::Fit,   '115/75',  13.3, 52.0, null,                                                                                                          1],
            ['patrick@email.com', '2026-04-30', EligibilityStatus::Fit,   '120/79',  15.2, 80.0, null,                                                                                                          2],

            // ── Round 3: May ────────────────────────
            ['hana@email.com',    '2026-05-05', EligibilityStatus::Fit,   '115/75',  13.6, 53.0, null,                                                                                                          0],
        ];

        // Build a lookup: email+date => screening config
        $lookup = [];
        foreach ($screeningMap as $row) {
            $lookup[$row[0] . '|' . $row[1]] = $row;
        }

        foreach ($completedAppointments as $appointment) {
            $email = $appointment->donor->user->email;
            $date  = $appointment->appointment_date->format('Y-m-d');
            $key   = $email . '|' . $date;

            if (!isset($lookup[$key])) continue;

            [, , $eligibility, $bp, $hgb, $weight, $remarks, $staffIdx] = $lookup[$key];

            Screening::create([
                'donor_id'           => $appointment->donor_id,
                'staff_id'           => $staffList[$staffIdx]->staff_id,
                'appointment_id'     => $appointment->appointment_id,
                'blood_pressure'     => $bp,
                'hemoglobin_level'   => $hgb,
                'weight'             => $weight,
                'eligibility_status' => $eligibility,
                'remarks'            => $remarks,
                'screening_date'     => $appointment->appointment_date,
                'created_at'         => $appointment->appointment_date,
                'updated_at'         => $appointment->appointment_date,
            ]);
        }
    }
}