<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $hospital = auth()->user()->hospital;
        return view('hospital.profile.edit', compact('hospital'));
    }

    public function update(Request $request)
    {
        $hospital = auth()->user()->hospital;
        $user     = auth()->user();

        $request->validate([
            'hospital_name'  => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'unique:hospitals,license_number,' . $hospital->hospital_id . ',hospital_id'],
            'address'        => ['required', 'string'],
            'contact_number' => ['required', 'string', 'max:20'],
        ]);

        $hospital->update($request->only([
            'hospital_name', 'license_number', 'address', 'contact_number',
        ]));

        $user->update(['name' => $request->hospital_name]);

        return back()->with('success', 'Hospital profile updated successfully.');
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
}