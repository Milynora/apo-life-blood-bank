<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use App\Models\Donor;
use App\Models\Hospital;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DonorController extends Controller
{
    public function index(Request $request)
{
    $users = User::with(['donor.bloodType', 'donor.donations', 'hospital'])
        ->whereIn('role', ['donor', 'hospital'])
        ->when($request->search, fn($q) =>
            $q->where(fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
        )
        ->when($request->role,   fn($q) => $q->where('role', $request->role))
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->latest()
        ->paginate(15)
        ->withQueryString();

    $stats = [
    'pending'   => User::whereIn('role', ['donor', 'hospital'])->where('status', 'pending')->count(),
    'active'    => User::whereIn('role', ['donor', 'hospital'])->where('status', 'active')->count(),
    'inactive'  => User::whereIn('role', ['donor', 'hospital'])->where('status', 'inactive')->count(),
    'rejected'  => User::whereIn('role', ['donor', 'hospital'])->where('status', 'rejected')->count(),
];

    return view('staff.donors.index', compact('users', 'stats'));
}

    // ── Donor methods ─────────────────────────────────────────────────────────
    public function show(Donor $donor)
{
    $donor->load(['user', 'bloodType', 'donations', 'appointments', 'screenings']);
    $bloodTypes = \App\Models\BloodType::orderBy('type_name')->get();
    return view('staff.donors.show', compact('donor', 'bloodTypes'));
}

    public function create()
    {
        $bloodTypes = BloodType::orderBy('type_name')->get();
        return view('staff.donors.create', compact('bloodTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'gender'         => ['required', 'in:male,female,other'],
            'date_of_birth'  => ['required', 'date', 'before:-17 years'],
            'blood_type_id'  => ['nullable', 'exists:blood_types,blood_type_id'],
            'address'        => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'regex:/^09\d{9}$/', 'size:11'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make('donor1234'),
            'role'     => UserRole::Donor,
            'status'   => UserStatus::Active,
        ]);

        Donor::create([
            'user_id'        => $user->id,
            'blood_type_id'  => $request->blood_type_id ?: null,
            'gender'         => $request->gender,
            'date_of_birth'  => $request->date_of_birth,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
        ]);

        return redirect()->route('staff.donors.index')
            ->with('success', 'Donor registered successfully.');
    }

    public function update(Request $request, Donor $donor)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:users,email,'. $donor->user->id],
            'gender'         => ['required', 'in:male,female,other'],
            'date_of_birth'  => ['required', 'date'],
            'blood_type_id'  => ['nullable', 'exists:blood_types,blood_type_id'],
            'address'        => ['nullable', 'string'],
            'contact_number' => ['nullable', 'string', 'regex:/^09\d{9}$/', 'size:11'],
        ]);

        $donor->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $donor->update([
            'gender'         => $request->gender,
            'date_of_birth'  => $request->date_of_birth,
            'blood_type_id'  => $request->blood_type_id ?: null,
            'address'        => $request->address,
            'contact_number' => $request->contact_number,
        ]);

        return redirect()->route('staff.donors.show', $donor)->with('success', 'Donor updated successfully.');
    }

    // ── Hospital methods ──────────────────────────────────────────────────────
    public function createHospital()
    {
        return view('staff.hospitals.create');
    }

    public function storeHospital(Request $request)
    {
        $request->validate([
            'hospital_name'  => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'address'        => ['nullable', 'string', 'max:500'],
            'contact_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'     => $request->hospital_name,
            'email'    => $request->email,
            'password' => Hash::make('hospital1234'),
            'role'     => UserRole::Hospital,
            'status'   => UserStatus::Active,
        ]);

        Hospital::create([
            'user_id'        => $user->id,
            'hospital_name'  => $request->hospital_name,
            'license_number' => $request->license_number,
            'address'        => $request->address,
            'contact_number' => $request->contact_number,
        ]);

        return redirect()->route('staff.donors.index')
            ->with('success', 'Hospital registered successfully.');
    }

    public function showHospital(Hospital $hospital)
    {
        $hospital->load(['user', 'requests.bloodType']);
        return view('staff.hospitals.show', compact('hospital'));
    }

    public function updateHospital(Request $request, Hospital $hospital)
    {
        $request->validate([
            'hospital_name'  => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'unique:users,email,'. $hospital->user->id],
            'address'        => ['nullable', 'string', 'max:500'],
            'contact_number' => ['nullable', 'string', 'max:20'],
        ]);

        $hospital->user->update([
            'name' => $request->hospital_name, 
            'email' => $request->email,
        ]);

        $hospital->update([
            'hospital_name'  => $request->hospital_name,
            'license_number' => $request->license_number,
            'address'        => $request->address,
            'contact_number' => $request->contact_number,
        ]);

        return redirect()->route('staff.hospitals.show', $hospital)->with('success', 'Hospital updated successfully.');
    }
}
