<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $donor      = auth()->user()->donor;
        $bloodTypes = BloodType::orderBy('type_name')->get();

        return view('donor.profile.edit', compact('donor', 'bloodTypes'));
    }

    public function updateProfile(Request $request)
    {
        $donor = auth()->user()->donor;
        $user  = auth()->user();

        $hasDonations = $donor->donations()->exists();

        $rules = [
            'name'           => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'date_of_birth'  => ['required', 'date', 'before:-17 years'],
            'gender'         => ['required', 'in:male,female,other'],
            'address'        => ['required', 'string'],
        ];

        // Blood type only editable if no donations yet
        if (!$hasDonations) {
            $rules['blood_type_id'] = ['nullable', 'exists:blood_types,blood_type_id'];
        }

        $request->validate($rules);

        $donorData = $request->only([
            'contact_number', 'date_of_birth', 'gender', 'address',
        ]);

        if (!$hasDonations) {
            $donorData['blood_type_id'] = $request->blood_type_id ?: null;
        }

        $donor->update($donorData);
        $user->update(['name' => $request->name]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $donor = auth()->user()->donor;

        // Delete old avatar if exists
        if ($donor->avatar && file_exists(public_path($donor->avatar))) {
            unlink(public_path($donor->avatar));
        }

        $file     = $request->file('avatar');
        $filename = 'avatar_' . $donor->donor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/avatars'), $filename);

        $donor->update(['avatar' => 'images/avatars/' . $filename]);

        return back()->with('success', 'Profile photo updated.');
    }

    public function updateEmail(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ]);

        $user->update(['email' => $request->email]);

        return back()->with('success', 'Email updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function removeAvatar(Request $request)
{
    $donor = $request->user()->donor;

    if ($donor->avatar) {
        \Storage::disk('public')->delete($donor->avatar);
        $donor->update(['avatar' => null]);
    }

    return back()->with('success', 'Profile photo removed.');
}
}