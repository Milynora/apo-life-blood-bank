<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Donor;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\Donation;
use App\Enums\EligibilityStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Screening extends Model
{
    protected $primaryKey = 'screening_id';
    protected $fillable = [
        'donor_id', 'staff_id', 'appointment_id', // Changed from donation_id
        'blood_pressure', 'hemoglobin_level', 'weight',
        'eligibility_status', 'remarks', 'screening_date',
    ];

    protected $casts = [
        'screening_date' => 'datetime', 
        'eligibility_status' => EligibilityStatus::class,
    ];

    public function donor():       BelongsTo { return $this->belongsTo(Donor::class, 'donor_id'); }
    public function staff():       BelongsTo { return $this->belongsTo(Staff::class, 'staff_id'); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class)->withDefault(); }
    
    // Relationship to the resulting donation
    public function donation():    HasOne    { return $this->hasOne(Donation::class, 'screening_id'); }
}