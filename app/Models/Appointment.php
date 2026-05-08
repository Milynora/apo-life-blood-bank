<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Appointment extends Model
{
    protected $primaryKey = 'appointment_id';

    protected $fillable = [
        'donor_id',
        'appointment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'status'           => AppointmentStatus::class,
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }

    public function screening(): HasOne
    {
        return $this->hasOne(Screening::class, 'appointment_id');
    }

    public function questionnaire(): HasOne
    {
        return $this->hasOne(Questionnaire::class, 'appointment_id', 'appointment_id');
    }

    public function donation()
    {
        return $this->hasOneThrough(
            Donation::class,
            Screening::class,
            'appointment_id',
            'screening_id',
            'appointment_id',
            'screening_id'
        );
    }
}