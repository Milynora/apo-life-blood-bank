<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Donor;
use App\Models\Staff;
use App\Models\Hospital;
use App\Enums\UserRole;
use App\Enums\UserStatus;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $bt = \App\Models\BloodType::pluck('blood_type_id', 'type_name');

        // ── Admin ──────────────────────────────────────────
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@email.com',
            'password' => Hash::make('admin1234'),
            'role'     => UserRole::Admin,
            'status'   => UserStatus::Active,
            'created_at' => '2026-01-01 08:00:00',
            'updated_at' => '2026-01-01 08:00:00',
        ]);

        // ── Staff ──────────────────────────────────────────
        $staffData = [
            ['Lena Marquez',   'lena@email.com',   '2026-01-02 08:00:00'],
            ['James Orcales',  'james@email.com',  '2026-01-02 08:00:00'],
            ['Tricia Navarro', 'tricia@email.com', '2026-01-03 08:00:00'],
        ];

        foreach ($staffData as [$name, $email, $ts]) {
            $user = User::create([
                'name'       => $name,
                'email'      => $email,
                'password'   => Hash::make('staff1234'),
                'role'       => UserRole::Staff,
                'status'     => UserStatus::Active,
                'created_at' => $ts,
                'updated_at' => $ts,
            ]);
            Staff::create([
                'user_id'    => $user->id,
                'created_at' => $ts,
                'updated_at' => $ts,
            ]);
        }

        // ── Donors ─────────────────────────────────────────
        // name, email, gender, dob, blood_type, contact, address, status, registered_at
        $donors = [
            // Active donors
            ['Rico Bautista',    'rico@email.com',    'male',   '1992-03-14', 'A+',  '09171110001', 'Brgy. Agdao, Davao City',         UserStatus::Active,   '2026-01-05 09:00:00'],
            ['Sasha Lim',        'sasha@email.com',   'female', '1995-07-22', 'B+',  '09182220002', 'Brgy. Buhangin, Davao City',      UserStatus::Active,   '2026-01-06 09:00:00'],
            ['Marco Villanueva', 'marco@email.com',   'male',   '1988-11-30', 'O+',  '09193330003', 'Brgy. Talomo, Davao City',        UserStatus::Active,   '2026-01-07 09:00:00'],
            ['Diane Torres',     'diane@email.com',   'female', '1993-05-18', 'AB+', '09204440004', 'Brgy. Paquibato, Davao City',     UserStatus::Active,   '2026-01-08 09:00:00'],
            ['Felix Reyes',      'felix@email.com',   'male',   '1990-09-02', 'O-',  '09215550005', 'Brgy. Toril, Davao City',         UserStatus::Active,   '2026-01-09 09:00:00'],
            ['Carla Mendez',     'carla@email.com',   'female', '1997-01-25', 'A-',  '09226660006', 'Brgy. Calinan, Davao City',       UserStatus::Active,   '2026-01-10 09:00:00'],
            ['Nathan Cruz',      'nathan@email.com',  'male',   '1985-06-10', 'B-',  '09237770007', 'Brgy. Marilog, Davao City',       UserStatus::Active,   '2026-01-11 09:00:00'],
            ['Bianca Flores',    'bianca@email.com',  'female', '1998-12-05', 'AB-', '09248880008', 'Brgy. Baguio, Davao City',        UserStatus::Active,   '2026-01-12 09:00:00'],
            ['Owen Dela Cruz',   'owen@email.com',    'male',   '1991-04-17', 'A+',  '09259990009', 'Brgy. Lasang, Davao City',        UserStatus::Active,   '2026-01-13 09:00:00'],
            ['Yvonne Santos',    'yvonne@email.com',  'female', '1994-08-28', 'O+',  '09261001010', 'Brgy. Bunawan, Davao City',       UserStatus::Active,   '2026-01-14 09:00:00'],
            ['Patrick Gomez',    'patrick@email.com', 'male',   '1987-02-09', 'B+',  '09271111011', 'Brgy. Catalunan Grande, Davao City', UserStatus::Active, '2026-01-15 09:00:00'],
            ['Hana Castillo',    'hana@email.com',    'female', '1996-10-03', 'A+',  '09282221012', 'Brgy. Matina, Davao City',        UserStatus::Active,   '2026-01-16 09:00:00'],
            ['Elias Ramos',      'elias@email.com',   'male',   '1989-07-14', 'O+',  '09293331013', 'Brgy. Mintal, Davao City',        UserStatus::Active,   '2026-01-17 09:00:00'],
            ['Trina Aquino',     'trina@email.com',   'female', '1993-03-20', 'AB+', '09204441014', 'Brgy. Tugbok, Davao City',        UserStatus::Active,   '2026-01-18 09:00:00'],
            ['Leo Fontaine',     'leo@email.com',     'male',   '1986-05-31', 'A-',  '09215551015', 'Brgy. Cabantian, Davao City',     UserStatus::Active,   '2026-01-19 09:00:00'],
            ['Mia Soriano',      'mia@email.com',     'female', '1999-09-15', 'B+',  '09226661016', 'Brgy. Indangan, Davao City',      UserStatus::Active,  '2026-04-20 09:00:00'],
            ['Zack Rivera',      'zack@email.com',    'male',   '2000-11-07', 'O+',  '09237771017', 'Brgy. Waan, Davao City',          UserStatus::Active,  '2026-04-22 09:00:00'],
            ['Nora Pascual',     'nora@email.com',    'female', '1990-06-18', 'A+',  '09248881018', 'Brgy. Communal, Davao City',      UserStatus::Active, '2026-02-01 09:00:00'],
            ['Brent Uy',         'brent@email.com',   'male',   '2005-02-14', 'B-',  '09259991019', 'Brgy. Tibungco, Davao City',      UserStatus::Active, '2026-03-10 09:00:00'],
            ['Gina Velasco',     'gina@email.com',    'female', '1984-08-22', 'O-',  '09261001020', 'Brgy. Panacan, Davao City',       UserStatus::Active, '2026-03-12 09:00:00'],
        ];

        foreach ($donors as [$name, $email, $gender, $dob, $bloodType, $contact, $address, $status, $ts]) {
            $user = User::create([
                'name'       => $name,
                'email'      => $email,
                'password'   => Hash::make('donor1234'),
                'role'       => UserRole::Donor,
                'status'     => $status,
                'created_at' => $ts,
                'updated_at' => $ts,
            ]);

            Donor::create([
                'user_id'        => $user->id,
                'blood_type_id'  => $bt[$bloodType],
                'gender'         => $gender,
                'date_of_birth'  => $dob,
                'contact_number' => $contact,
                'address'        => $address,
                'created_at'     => $ts,
                'updated_at'     => $ts,
            ]);
        }

        // ── Hospitals ──────────────────────────────────────
        $hospitals = [
            [
                'name'    => 'Davao Medical Center',
                'email'   => 'davaomedical@email.com',
                'license' => 'DOH-XI-2026-001',
                'contact' => '09271234001',
                'address' => 'J.P. Laurel Ave., Poblacion District, Davao City',
                'status'  => UserStatus::Active,
                'ts'      => '2026-01-03 08:00:00',
            ],
            [
                'name'    => 'Brokenshire Medical Center',
                'email'   => 'brokenshire@email.com',
                'license' => 'DOH-XI-2026-002',
                'contact' => '09282345002',
                'address' => 'Madapo Hills, Davao City',
                'status'  => UserStatus::Active,
                'ts'      => '2026-01-04 08:00:00',
            ],
            [
                'name'    => 'San Pedro Hospital',
                'email'   => 'sanpedro@email.com',
                'license' => 'DOH-XI-2026-003',
                'contact' => '09293456003',
                'address' => 'Pedro Claver St., Davao City',
                'status'  => UserStatus::Active,
                'ts'      => '2026-04-25 08:00:00',
            ],
        ];

        foreach ($hospitals as $h) {
            $user = User::create([
                'name'       => $h['name'],
                'email'      => $h['email'],
                'password'   => Hash::make('hospital1234'),
                'role'       => UserRole::Hospital,
                'status'     => $h['status'],
                'created_at' => $h['ts'],
                'updated_at' => $h['ts'],
            ]);

            Hospital::create([
                'user_id'        => $user->id,
                'hospital_name'  => $h['name'],
                'license_number' => $h['license'],
                'contact_number' => $h['contact'],
                'address'        => $h['address'],
                'created_at'     => $h['ts'],
                'updated_at'     => $h['ts'],
            ]);
        }
    }
}