<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    protected $primaryKey = 'donor_id';
    protected $fillable = [
        'user_id', 'blood_type_id', 
        'gender', 'date_of_birth', 'address', 'contact_number', 'avatar',
    ];

    protected $casts = [
    'date_of_birth' => 'date',
];

    // Updated: Pulls name from the related User record
    public function getNameAttribute(): string 
    { 
        return $this->user ? $this->user->name : 'Unknown Donor'; 
    }

    public function user():         BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function bloodType():    BelongsTo { return $this->belongsTo(BloodType::class, 'blood_type_id'); }
    public function appointments(): HasMany   { return $this->hasMany(Appointment::class, 'donor_id'); }
    public function donations():    HasMany   { return $this->hasMany(Donation::class, 'donor_id'); }
    public function screenings():   HasMany   { return $this->hasMany(Screening::class, 'donor_id'); }
}