<?php

namespace App\Models;

use App\Enums\BloodUnitStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\RequestBloodUnit;
use App\Models\Donation;
use App\Models\BloodType;
use App\Models\BloodRequest;

class BloodUnit extends Model
{
    protected $primaryKey = 'blood_unit_id';

    protected $fillable = [
        'donation_id',
        'blood_type_id',
        'stored_date',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'stored_date' => 'datetime',
        'expiry_date' => 'datetime',
        'status'      => BloodUnitStatus::class,
    ];

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', BloodUnitStatus::Available);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', BloodUnitStatus::Expired);
    }

    public function scopeNotExpired($query)
    {
        return $query->where('expiry_date', '>=', now());
    }

    public function scopeExpiringSoon($query, int $days = 7)
    {
        return $query->where('status', BloodUnitStatus::Available)
                     ->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    // Relationships
    public function donation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Donation::class, 'donation_id', 'donation_id');
    }

    public function bloodType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BloodType::class, 'blood_type_id', 'blood_type_id');
    }

    public function bloodRequests(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            BloodRequest::class,
            'request_blood_units',
            'blood_unit_id',
            'request_id'
        )->using(RequestBloodUnit::class);
    }
}