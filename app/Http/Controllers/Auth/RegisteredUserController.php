<?php
namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\{User, Donor, Hospital};
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    // app/Http/Controllers/Auth/RegisteredUserController.php

public function store(Request $request): RedirectResponse
{
    $role = $request->input('role', 'donor');

    $baseRules = [
        'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role'     => ['required', 'in:donor,hospital'],
        'terms'    => ['accepted'],
    ];

    $donorRules = [
        'name'                 => ['required', 'string', 'max:255'],
        'gender'               => ['required', 'in:male,female,other'],
        'date_of_birth'        => ['required', 'date_format:m/d/Y', 'before:-17 years'],
        'd_address'            => ['required', 'string'],
        'donor_contact_number' => ['required', 'string', 'regex:/^09\d{9}$/'],
        'blood_type_id'        => ['nullable', 'exists:blood_types,blood_type_id'], // ← was required, now nullable
    ];

    $hospitalRules = [
        'hospital_name'           => ['required', 'string', 'max:255'],
        'license_number'          => ['required', 'string', 'unique:hospitals,license_number'],
        'h_address'               => ['required', 'string'],
        'hospital_contact_number' => ['required', 'string', 'regex:/^(09\d{9}|\d{7,10})$/'],
    ];

    $validated = $request->validate(
        array_merge($baseRules, $role === 'donor' ? $donorRules : $hospitalRules)
    );

    // Donors go straight to Active — no approval needed
    // Hospitals stay Pending until admin approves
    $status = $role === 'donor' ? UserStatus::Active : UserStatus::Pending;

    $user = User::create([
        'name'     => $role === 'donor'
            ? trim($validated['name'])
            : trim($validated['hospital_name']),
        'email'    => trim($validated['email']),
        'password' => Hash::make($validated['password']),
        'role'     => UserRole::from($role),
        'status'   => $status,
    ]);

    if ($role === 'donor') {
        try {
            $date = Carbon::createFromFormat('m/d/Y', $validated['date_of_birth'])->format('Y-m-d');
        } catch (\Exception $e) {
            return back()->withErrors(['date_of_birth' => 'Invalid date format.'])->withInput();
        }

        Donor::create([
            'user_id'        => $user->id,
            'blood_type_id'  => $validated['blood_type_id'] ?? null, // ← nullable now
            'gender'         => $validated['gender'],
            'date_of_birth'  => $date,
            'address'        => trim($validated['d_address']),
            'contact_number' => $validated['donor_contact_number'],
        ]);

        // Log the donor in immediately and send to their dashboard
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('donor.dashboard')
            ->with('success', 'Welcome to Apo Life! Your account has been created.');

    } else {
        Hospital::create([
            'user_id'        => $user->id,
            'hospital_name'  => trim($validated['hospital_name']),
            'license_number' => $validated['license_number'],
            'address'        => trim($validated['h_address']),
            'contact_number' => $validated['hospital_contact_number'],
        ]);

        // Only for hospital registrations
if ($request->role === 'hospital') {
    $admins = \App\Models\User::where('role', 'admin')
        ->where('status', 'active')
        ->get();

    foreach ($admins as $admin) {
        $admin->notify(new \App\Notifications\NewHospitalRegistered($user));
    }
}

        // Do NOT log hospital in — they need admin approval first
        return redirect()->route('login')
            ->with('status', 'Registration submitted. You will be notified by email once your account is approved.');
    }
}
}