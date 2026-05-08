<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role->value, ['admin', 'staff', 'donor']);
    }

    public function view(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin()
            || $user->isStaff()
            || ($user->isDonor() && $user->donor->donor_id === $appointment->donor_id);
    }

    public function create(User $user): bool
    {
        return $user->isDonor() && $user->isActive();
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin()
            || $user->isStaff()
            || ($user->isDonor() && $user->donor->donor_id === $appointment->donor_id);
    }
}