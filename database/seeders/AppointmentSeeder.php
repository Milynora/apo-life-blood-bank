<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Donor;
use App\Enums\AppointmentStatus;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $donors = Donor::with('user')->get()->keyBy(fn($d) => $d->user->email);

        // email, appointment_date, status, notes, created_at
        // Round 1: Jan donations
        // Round 2: Mar donations (56+ days after Jan)
        // Round 3: Apr/May donations (56+ days after Mar)
        // Plus: cancelled, no_show, rejected, pending, approved for variety
        $data = [

            // ── Round 1: January ─────────────────────────────
            ['rico@email.com',    '2026-01-20 09:00:00', AppointmentStatus::Completed, null,                                              '2026-01-17 08:00:00'],
            ['sasha@email.com',   '2026-01-22 09:00:00', AppointmentStatus::Completed, null,                                              '2026-01-19 08:00:00'],
            ['marco@email.com',   '2026-01-24 09:00:00', AppointmentStatus::Completed, null,                                              '2026-01-21 08:00:00'],
            ['diane@email.com',   '2026-01-26 09:00:00', AppointmentStatus::Completed, null,                                              '2026-01-23 08:00:00'],
            ['felix@email.com',   '2026-01-28 09:00:00', AppointmentStatus::Completed, null,                                              '2026-01-25 08:00:00'],
            ['carla@email.com',   '2026-01-30 09:00:00', AppointmentStatus::Completed, null,                                              '2026-01-27 08:00:00'],

            // ── Round 1: February ────────────────────────────
            ['nathan@email.com',  '2026-02-02 09:00:00', AppointmentStatus::Completed, null,                                              '2026-01-30 08:00:00'],
            ['bianca@email.com',  '2026-02-04 09:00:00', AppointmentStatus::Completed, null,                                              '2026-02-01 08:00:00'],
            ['owen@email.com',    '2026-02-06 09:00:00', AppointmentStatus::Completed, null,                                              '2026-02-03 08:00:00'],
            ['yvonne@email.com',  '2026-02-08 09:00:00', AppointmentStatus::Completed, null,                                              '2026-02-05 08:00:00'],
            ['patrick@email.com', '2026-02-10 09:00:00', AppointmentStatus::Completed, null,                                              '2026-02-07 08:00:00'],
            ['hana@email.com',    '2026-02-12 09:00:00', AppointmentStatus::Completed, null,                                              '2026-02-09 08:00:00'],
            ['elias@email.com',   '2026-02-14 09:00:00', AppointmentStatus::Completed, null,                                              '2026-02-11 08:00:00'],
            ['trina@email.com',   '2026-02-16 09:00:00', AppointmentStatus::Completed, null,                                              '2026-02-13 08:00:00'],
            ['leo@email.com',     '2026-02-18 09:00:00', AppointmentStatus::Completed, null,                                              '2026-02-15 08:00:00'],

            // Carla retry after failed donation (no 56-day restriction after failure)
            ['carla@email.com',   '2026-02-20 09:00:00', AppointmentStatus::Completed, null,                                              '2026-02-17 08:00:00'],
            // Bianca retry after unfit (no restriction after unfit)
            ['bianca@email.com',  '2026-02-25 09:00:00', AppointmentStatus::Completed, null,                                              '2026-02-22 08:00:00'],

            // ── Misc statuses in Feb ─────────────────────────
            ['nora@email.com',    '2026-02-15 09:00:00', AppointmentStatus::Cancelled,  'Cancelled due to donor request.',                '2026-02-12 08:00:00'],
            ['elias@email.com',   '2026-02-28 09:00:00', AppointmentStatus::NoShow,     null,                                             '2026-02-25 08:00:00'],

            // ── Round 2: March (56+ days after Jan/Feb donations) ──
            ['rico@email.com',    '2026-03-17 09:00:00', AppointmentStatus::Completed, null,                                              '2026-03-14 08:00:00'],
            ['sasha@email.com',   '2026-03-19 09:00:00', AppointmentStatus::Completed, null,                                              '2026-03-16 08:00:00'],
            ['marco@email.com',   '2026-03-21 09:00:00', AppointmentStatus::Completed, null,                                              '2026-03-18 08:00:00'],
            ['diane@email.com',   '2026-03-23 09:00:00', AppointmentStatus::Completed, null,                                              '2026-03-20 08:00:00'],
            ['felix@email.com',   '2026-03-25 09:00:00', AppointmentStatus::Completed, null,                                              '2026-03-22 08:00:00'],
            ['nathan@email.com',  '2026-03-30 09:00:00', AppointmentStatus::Completed, null,                                              '2026-03-27 08:00:00'],
            ['patrick@email.com', '2026-03-05 09:00:00', AppointmentStatus::Completed, null,                                              '2026-03-02 08:00:00'],
            ['hana@email.com',    '2026-03-10 09:00:00', AppointmentStatus::Completed, null,                                              '2026-03-07 08:00:00'],

            // Misc statuses in March
            ['marco@email.com',   '2026-03-12 09:00:00', AppointmentStatus::Rejected,  'Date requested is a holiday. Please reschedule.', '2026-03-09 08:00:00'],
            ['sasha@email.com',   '2026-03-28 09:00:00', AppointmentStatus::Cancelled, 'Cancelled — donor unavailable.',                  '2026-03-25 08:00:00'],

            // ── Round 3: April (56+ days after Feb/Mar donations) ──
            ['owen@email.com',    '2026-04-03 09:00:00', AppointmentStatus::Completed, null,                                              '2026-03-31 08:00:00'],
            ['yvonne@email.com',  '2026-04-05 09:00:00', AppointmentStatus::Completed, null,                                              '2026-04-02 08:00:00'],
            ['elias@email.com',   '2026-04-11 09:00:00', AppointmentStatus::Completed, null,                                              '2026-04-08 08:00:00'],
            ['trina@email.com',   '2026-04-13 09:00:00', AppointmentStatus::Completed, null,                                              '2026-04-10 08:00:00'],
            ['leo@email.com',     '2026-04-15 09:00:00', AppointmentStatus::Completed, null,                                              '2026-04-12 08:00:00'],
            ['carla@email.com',   '2026-04-17 09:00:00', AppointmentStatus::Completed, null,                                              '2026-04-14 08:00:00'],
            ['bianca@email.com',  '2026-04-22 09:00:00', AppointmentStatus::Completed, null,                                              '2026-04-19 08:00:00'],
            ['patrick@email.com', '2026-04-30 09:00:00', AppointmentStatus::Completed, null,                                              '2026-04-27 08:00:00'],

            // Misc statuses in April
            ['rico@email.com',    '2026-04-20 09:00:00', AppointmentStatus::NoShow,    null,                                              '2026-04-17 08:00:00'],
            ['nathan@email.com',  '2026-04-25 09:00:00', AppointmentStatus::Rejected,  'Slot fully booked. Please choose another date.',  '2026-04-22 08:00:00'],

            // ── Round 3: May (56+ days after Mar donations) ──
            ['hana@email.com',    '2026-05-05 09:00:00', AppointmentStatus::Completed, null,                                              '2026-05-02 08:00:00'],

            // Upcoming approved & pending
            ['rico@email.com',    '2026-05-15 09:00:00', AppointmentStatus::Approved,  null,                                              '2026-05-05 08:00:00'],
            ['sasha@email.com',   '2026-05-20 09:00:00', AppointmentStatus::Approved,  null,                                              '2026-05-06 08:00:00'],
            ['mia@email.com',     '2026-05-22 09:00:00', AppointmentStatus::Pending,   null,                                              '2026-05-06 08:00:00'],
            ['zack@email.com',    '2026-05-25 09:00:00', AppointmentStatus::Pending,   null,                                              '2026-05-07 08:00:00'],
        ];

        foreach ($data as [$email, $date, $status, $notes, $createdAt]) {
            $donor = $donors[$email] ?? null;
            if (!$donor) continue;

            Appointment::create([
                'donor_id'         => $donor->donor_id,
                'appointment_date' => $date,
                'status'           => $status,
                'notes'            => $notes,
                'created_at'       => $createdAt,
                'updated_at'       => $createdAt,
            ]);
        }
    }
}