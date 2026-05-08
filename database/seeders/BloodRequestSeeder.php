<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodRequest;
use App\Models\BloodUnit;
use App\Models\Hospital;
use App\Enums\RequestStatus;
use App\Enums\BloodUnitStatus;

class BloodRequestSeeder extends Seeder
{
    public function run(): void
    {
        $hospitals = Hospital::with('user')->get()->keyBy(function ($h) {
            return $h->user->email;
        });

        $bt = \App\Models\BloodType::pluck('blood_type_id', 'type_name');

        // hospital_email, blood_type, qty, urgency, fulfillment_type, request_date, status, remarks
        // Fulfilled/PartiallyFulfilled requests use real available units — qty must not exceed what DonationSeeder created
        // Blood units available per type after DonationSeeder (all Available):
        //   A+: rico(A+) + 3 extras = 4
        //   B+: sasha(B+) + 2 extras = 3
        //   O+: marco(O+) + yvonne(O+) + elias(O+) + 3 extras = 6
        //   AB+: diane(AB+) + trina(AB+) + 2 extras = 4
        //   O-: felix(O-) + 2 extras = 3
        //   A-: carla failed so 0 from main + 1 extra = 1
        //   B-: nathan(B-) + 2 extras = 3
        //   AB-: bianca(AB-) + 2 extras = 3 (bianca unfit so no donation unit) = 2 extras only = 2
        //   Note: leo(A-), owen(A+), patrick failed, hana unfit
        //   Recount:
        //     A+:  rico + owen + 3 extras = 5
        //     B+:  sasha + 2 extras = 3
        //     O+:  marco + yvonne + elias + 3 extras = 6
        //     AB+: diane + trina + 2 extras = 4
        //     O-:  felix + 2 extras = 3
        //     A-:  leo + 1 extra = 2
        //     B-:  nathan + 2 extras = 3
        //     AB-: bianca(unfit,no unit) + 2 extras = 2
        // Fulfilled requests must not request more units than available above
        $requests = [
            // Davao Medical Center
            ['davaomedical@email.com', 'O+',  3, 'urgent',    'pickup',   '2026-03-10', RequestStatus::Fulfilled,          'Post-surgery patients needing immediate transfusion.'],
            ['davaomedical@email.com', 'A+',  2, 'routine',   'pickup',   '2026-03-18', RequestStatus::Fulfilled,          'Scheduled elective surgery.'],
            ['davaomedical@email.com', 'B+',  1, 'routine',   'delivery', '2026-03-25', RequestStatus::Fulfilled,          null],
            ['davaomedical@email.com', 'O-',  2, 'emergency', 'pickup',   '2026-04-02', RequestStatus::Rejected,           'Insufficient O- stock available at this time.'],
            ['davaomedical@email.com', 'AB+', 2, 'urgent',    'pickup',   '2026-04-08', RequestStatus::PartiallyFulfilled, 'Trauma patient — partial fulfillment accepted.'],
            ['davaomedical@email.com', 'A-',  1, 'routine',   'delivery', '2026-04-15', RequestStatus::Fulfilled,          null],
            ['davaomedical@email.com', 'O+',  2, 'urgent',    'pickup',   '2026-04-22', RequestStatus::Approved,           null],
            ['davaomedical@email.com', 'B-',  2, 'emergency', 'pickup',   '2026-05-01', RequestStatus::Pending,            'Emergency — patient in critical condition.'],
            ['davaomedical@email.com', 'AB-', 1, 'routine',   'delivery', '2026-05-03', RequestStatus::Pending,            null],

            // Brokenshire Medical Center
            ['brokenshire@email.com',  'A+',  2, 'routine',   'pickup',   '2026-03-20', RequestStatus::Fulfilled,          'Pre-scheduled surgery.'],
            ['brokenshire@email.com',  'O+',  3, 'urgent',    'delivery', '2026-03-28', RequestStatus::PartiallyFulfilled, 'Multiple trauma cases — partial fulfillment due to limited stock.'],
            ['brokenshire@email.com',  'B+',  1, 'routine',   'pickup',   '2026-04-05', RequestStatus::Rejected,           'Request incomplete — missing supporting documents.'],
            ['brokenshire@email.com',  'O-',  2, 'emergency', 'pickup',   '2026-04-12', RequestStatus::PartiallyFulfilled, 'ICU patient — partially fulfilled with available stock.'],
            ['brokenshire@email.com',  'A+',  1, 'routine',   'delivery', '2026-04-20', RequestStatus::Approved,           null],
            ['brokenshire@email.com',  'AB+', 2, 'urgent',    'pickup',   '2026-04-30', RequestStatus::Pending,            'Oncology department request.'],
            ['brokenshire@email.com',  'O+',  1, 'routine',   'pickup',   '2026-05-04', RequestStatus::Pending,            null],
        ];

        foreach ($requests as [$email, $type, $qty, $urgency, $fulfillment, $date, $status, $remarks]) {
            $hospital = $hospitals[$email] ?? null;
            if (!$hospital) continue;

            $req = BloodRequest::create([
                'hospital_id'      => $hospital->hospital_id,
                'blood_type_id'    => $bt[$type],
                'quantity'         => $qty,
                'urgency'          => $urgency,
                'fulfillment_type' => $fulfillment,
                'request_date'     => $date,
                'status'           => $status,
                'remarks'          => $remarks,
                'created_at'       => $date . ' 08:00:00',
                'updated_at'       => $date . ' 08:00:00',
            ]);

            if ($status === RequestStatus::Fulfilled) {
                // Attach exactly qty units and mark them Used
                $units = BloodUnit::where('blood_type_id', $bt[$type])
                    ->where('status', BloodUnitStatus::Available)
                    ->whereDoesntHave('bloodRequests')
                    ->take($qty)
                    ->get();

                foreach ($units as $unit) {
                    \DB::table('request_blood_units')->insert([
                        'request_id'    => $req->request_id,
                        'blood_unit_id' => $unit->blood_unit_id,
                    ]);
                    $unit->update([
                        'status'     => BloodUnitStatus::Used,
                        'updated_at' => $date . ' 09:00:00',
                    ]);
                }

            } elseif ($status === RequestStatus::PartiallyFulfilled) {
                // Attach 1 unit and mark it Reserved
                $unit = BloodUnit::where('blood_type_id', $bt[$type])
                    ->where('status', BloodUnitStatus::Available)
                    ->whereDoesntHave('bloodRequests')
                    ->first();

                if ($unit) {
                    \DB::table('request_blood_units')->insert([
                        'request_id'    => $req->request_id,
                        'blood_unit_id' => $unit->blood_unit_id,
                    ]);
                    $unit->update([
                        'status'     => BloodUnitStatus::Reserved,
                        'updated_at' => $date . ' 09:00:00',
                    ]);
                }
            }
        }
    }
}