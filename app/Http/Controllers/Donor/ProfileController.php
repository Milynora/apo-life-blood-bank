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

    // Debug: check what Cloudinary config is seeing
    $cloudinaryUrl = config('cloudinary.cloud_url');
    \Log::error('CLOUDINARY CONFIG: ' . var_export($cloudinaryUrl, true));

    if (!$cloudinaryUrl) {
        return back()->with('error', 'Cloudinary not configured. URL is null.');
    }

    try {
        if ($donor->avatar_public_id) {
            cloudinary()->destroy($donor->avatar_public_id);
        }

        $result = cloudinary()->upload($request->file('avatar')->getRealPath(), [
            'folder'        => 'apo-life/avatars',
            'resource_type' => 'image',
        ]);

        $donor->update([
            'avatar'           => $result->getSecurePath(),
            'avatar_public_id' => $result->getPublicId(),
        ]);

        return back()->with('success', 'Profile photo updated.');

    } catch (\Exception $e) {
        \Log::error('Cloudinary upload failed: ' . $e->getMessage());
        return back()->with('error', 'Debug: ' . $e->getMessage());
    }
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

    if ($donor->avatar_public_id) {
        try {
            cloudinary()->destroy($donor->avatar_public_id);
        } catch (\Exception $e) {
            \Log::warning('Cloudinary delete failed: ' . $e->getMessage());
        }

        $donor->update([
            'avatar'           => null,
            'avatar_public_id' => null,
        ]);
    }

    return back()->with('success', 'Profile photo removed.');
}
}