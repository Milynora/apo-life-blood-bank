<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BloodTypeSeeder::class,
            UserSeeder::class,
            AppointmentSeeder::class,
            ScreeningSeeder::class,
            DonationSeeder::class,
            BloodRequestSeeder::class,
        ]);
    }
}