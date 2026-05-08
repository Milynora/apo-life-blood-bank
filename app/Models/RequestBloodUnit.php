<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\BloodRequest;
use App\Models\BloodUnit;

class RequestBloodUnit extends Pivot
{
    protected $table = 'request_blood_units';

    public $timestamps = false;

    public function request(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BloodRequest::class, 'request_id');
    }

    public function bloodUnit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BloodUnit::class, 'blood_unit_id');
    }
}