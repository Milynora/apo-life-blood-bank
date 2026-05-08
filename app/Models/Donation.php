<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\DonationStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Donation extends Model
{
    protected $primaryKey = 'donation_id';
    protected $fillable = [
    'donor_id', 'staff_id', 'screening_id', 'blood_type_id',
    'donation_date', 'volume', 'status', 'remarks',
];

    protected $casts = [
        'donation_date' => 'date', 
        'status'        => DonationStatus::class,
    ];

    public function donor():     BelongsTo { return $this->belongsTo(Donor::class, 'donor_id'); }
    public function staff():     BelongsTo { return $this->belongsTo(Staff::class, 'staff_id'); }
    public function screening(): BelongsTo { return $this->belongsTo(Screening::class, 'screening_id'); }
    public function bloodUnits(): HasMany  { return $this->hasMany(BloodUnit::class, 'donation_id'); }
}