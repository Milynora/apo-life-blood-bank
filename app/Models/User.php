<?php
namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Donor;
use App\Models\Staff;
use App\Models\Hospital;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'role'              => UserRole::class,
        'status'            => UserStatus::class,
    ];

    // Role helpers
    public function isAdmin():    bool { return $this->role === UserRole::Admin; }
    public function isStaff():    bool { return $this->role === UserRole::Staff; }
    public function isDonor():    bool { return $this->role === UserRole::Donor; }
    public function isHospital(): bool { return $this->role === UserRole::Hospital; }

    // Status helpers - Updated to match new Enum cases
    public function isActive():   bool { return $this->status === UserStatus::Active; }
    public function isPending():  bool { return $this->status === UserStatus::Pending; }
    public function isInactive(): bool { return $this->status === UserStatus::Inactive; }
    public function isRejected(): bool { return $this->status === UserStatus::Rejected; }

    // Relationships
    public function donor():    HasOne { return $this->hasOne(Donor::class, 'user_id'); }
    public function staff():    HasOne { return $this->hasOne(Staff::class, 'user_id'); }
    public function hospital(): HasOne { return $this->hasOne(Hospital::class, 'user_id'); }
}