<?php
namespace App\Policies;

use App\Models\Donation;
use App\Models\User;

class DonationPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role->value, ['admin', 'staff']);
    }

    public function view(User $user, Donation $donation): bool
    {
        return $user->isAdmin()
            || $user->isStaff()
            || ($user->isDonor() && $user->donor->donor_id === $donation->donor_id);
    }

    public function create(User $user): bool
    {
        return $user->isStaff() || $user->isAdmin();
    }

    public function update(User $user, Donation $donation): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}