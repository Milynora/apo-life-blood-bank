<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\RequestBloodUnit;

class BloodRequest extends Model
{
    protected $table      = 'requests';
    protected $primaryKey = 'request_id';

    protected $fillable = [
    'hospital_id',
    'blood_type_id',
    'quantity',
    'urgency',
    'fulfillment_type',
    'request_date',
    'status',
    'remarks',
    'needed_by',
];

    protected $casts = [
        'request_date' => 'date',
        'status'       => RequestStatus::class,
        'needed_by' => 'date',
    ];

    // Accessors
    public function getFulfilledCountAttribute(): int
    {
        return $this->bloodUnits()->count();
    }

    public function getRemainingAttribute(): int
    {
        return max(0, $this->quantity - $this->fulfilled_count);
    }

    public function isFullyFulfilled(): bool
    {
        return $this->remaining === 0;
    }

    // Relationships
    public function hospital(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'hospital_id', 'hospital_id');
    }

    public function bloodType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BloodType::class, 'blood_type_id', 'blood_type_id');
    }

    public function bloodUnits(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            BloodUnit::class,
            'request_blood_units',
            'request_id',
            'blood_unit_id'
        )->using(RequestBloodUnit::class);
    }
    public function getRouteKeyName()
{
    return 'request_id';
}
}

