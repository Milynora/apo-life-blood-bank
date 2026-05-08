<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloodTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        foreach ($types as $type) {
            DB::table('blood_types')->insertOrIgnore([
                'type_name'  => $type,
                'created_at' => '2026-01-01 08:00:00',
                'updated_at' => '2026-01-01 08:00:00',
            ]);
        }
    }
}