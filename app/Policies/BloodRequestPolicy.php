<?php

namespace App\Policies;

use App\Models\BloodRequest;
use App\Models\User;

class BloodRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role->value, ['admin', 'staff', 'hospital']);
    }

    public function view(User $user, BloodRequest $request): bool
    {
        return $user->isAdmin()
            || $user->isStaff()
            || ($user->isHospital() && $user->hospital->hospital_id === $request->hospital_id);
    }

    public function create(User $user): bool
    {
        return $user->isHospital() && $user->isActive();
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function reject(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function fulfill(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }
}