<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Donor, Hospital, Staff, BloodType};
use App\Enums\{UserRole, UserStatus};
use App\Notifications\AccountApproved;
use App\Notifications\AccountRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // ── INDEX ────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $showTrashed = $request->status === 'deleted';

$users = User::query()
    ->when($showTrashed, fn($q) => $q->onlyTrashed())
    ->when(!$showTrashed, function($q) use ($request) {
        $q->when($request->role,   fn($q) => $q->where('role', $request->role))
          ->when($request->status, fn($q) => $q->where('status', $request->status))
          ->when($request->search, fn($q) => $q->where(function($q) use ($request) {
              $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%");
          }));
    })
    ->when(!$showTrashed && $request->search, fn($q) => $q->where(function($q) use ($request) {
        $q->where('name', 'like', "%{$request->search}%")
          ->orWhere('email', 'like', "%{$request->search}%");
    }))
    ->with(['donor.bloodType', 'hospital', 'staff'])
    ->latest()
    ->paginate(15)
    ->withQueryString();

        $stats = [
    'pending'  => User::where('status', UserStatus::Pending)->count(),
    'active'   => User::where('status', UserStatus::Active)->count(),
    'inactive' => User::where('status', UserStatus::Inactive)->count(),
    'rejected' => User::where('status', UserStatus::Rejected)->count(),
    'deleted'  => User::onlyTrashed()->count(),
];

        $bloodTypes = BloodType::orderBy('type_name')->get();

        return view('admin.users.index', compact('users', 'bloodTypes', 'stats'));
    }

    public function restore(string $id)
{
    $user = User::onlyTrashed()->findOrFail($id);
    $user->restore();
    return back()->with('success', "{$user->name} has been restored.");
}

public function reactivate(User $user)
{
    $user->update(['status' => UserStatus::Active]);

    $user->notify(new \App\Notifications\AccountApproved($user));

    return back()->with('success', "{$user->name}'s account has been reactivated.");
}

    // ── SHOW ─────────────────────────────────────────────────────
    public function show(User $user)
{
    $user->load([
        'donor.bloodType',
        'donor.donations.staff',
        'donor.donations.screening',
        'donor.screenings',
        'donor.appointments.donation',
        'hospital.requests.bloodType',
        'staff',
    ]);
    $bloodTypes = BloodType::orderBy('type_name')->get();
    return view('admin.users.show', compact('user', 'bloodTypes'));
}

    // ── CREATE DONOR ─────────────────────────────────────────────
    public function createDonor()
    {
        $bloodTypes = BloodType::orderBy('type_name')->get();
        return view('admin.users.create-donor', compact('bloodTypes'));
    }

    public function storeDonor(Request $request)
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
        'blood_type_id'  => $request->blood_type_id,
        'gender'         => $request->gender,
        'date_of_birth'  => $request->date_of_birth,
        'address'        => $request->address,
        'contact_number' => $request->contact_number,
    ]);

    $user->notify(new AccountApproved());

    return redirect()->route('admin.users.index')
        ->with('success', 'Donor account created successfully.');
}

    // ── CREATE HOSPITAL ──────────────────────────────────────────
    public function createHospital()
    {
        return view('admin.users.create-hospital');
    }

    public function storeHospital(Request $request)
{
    $request->validate([
        'hospital_name'  => ['required', 'string', 'max:255'],
        'email'          => ['required', 'email', 'unique:users,email'],
        'license_number' => ['required', 'string', 'unique:hospitals,license_number'],
        'address'        => ['nullable', 'string', 'max:255'],
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

    $user->notify(new AccountApproved());

    return redirect()->route('admin.users.index')
        ->with('success', 'Hospital account created successfully.');
}

    // ── CREATE STAFF ─────────────────────────────────────────────
    public function createStaff()
    {
        return view('admin.users.create-staff');
    }

    public function storeStaff(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => UserRole::Staff,
            'status'   => UserStatus::Active,
        ]);

        Staff::create(['user_id' => $user->id]);

        // Notify staff their account is ready
        $user->notify(new AccountApproved());

        return redirect()->route('admin.users.index')
            ->with('success', 'Staff account created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $request->validate($rules);

        $user->update(['name' => $request->name, 'email' => $request->email]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update donor profile
        if ($user->isDonor() && $user->donor) {
            $request->validate([
                'gender'         => ['required', 'in:male,female,other'],
                'date_of_birth'  => ['required', 'date'],
                'blood_type_id'  => ['nullable', 'exists:blood_types,blood_type_id'],
                'contact_number' => ['nullable', 'string', 'regex:/^09\d{9}$/', 'size:11'],
            ]);

            $user->donor->update($request->only([
                'gender', 'date_of_birth',
                'blood_type_id', 'address', 'contact_number',
            ]));
        }

        // Update hospital profile
        if ($user->isHospital() && $user->hospital) {
            $request->validate([
                'hospital_name'  => ['required', 'string', 'max:255'],
                'license_number' => ['required', 'string',
                    'unique:hospitals,license_number,' . $user->hospital->hospital_id . ',hospital_id'],
                'address'        => ['nullable', 'string'],
            ]);

            $user->hospital->update($request->only([
                'hospital_name', 'license_number', 'address', 'contact_number',
            ]));
        }

        return redirect()->route('admin.users.show', $user)
    ->with('success', 'User updated successfully.');
    }

    // ── APPROVE ──────────────────────────────────────────────────
    public function approve(User $user)
    {
        $user->update(['status' => UserStatus::Active]);

        // Notify user their account is approved
        $user->notify(new AccountApproved());

        return back()->with('success', "{$user->name} has been approved.");
    }

    // ── REJECT ───────────────────────────────────────────────────
    public function reject(Request $request, User $user)
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update(['status' => UserStatus::Rejected]);

        // Notify user their account was rejected with reason
        $user->notify(new AccountRejected($request->reason));

        return back()->with('success', "{$user->name} has been rejected.");
    }

    // ── SUSPEND ──────────────────────────────────────────────────
    public function suspend(User $user)
    {
        $user->update(['status' => UserStatus::Inactive]);

        // Notify user their account has been suspended
        $user->notify(new AccountRejected('Your account has been suspended by an administrator.'));

        return back()->with('success', "{$user->name} has been suspended.");
    }

    // ── DELETE ───────────────────────────────────────────────────
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$name} deleted successfully.");
    }

    
}