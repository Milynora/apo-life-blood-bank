<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    protected $primaryKey = 'blood_type_id';
    protected $fillable = ['type_name'];

    public function donors():     \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Donor::class, 'blood_type_id'); }
    public function bloodUnits(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(BloodUnit::class, 'blood_type_id'); }
    public function bloodRequests():   \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(BloodRequest::class, 'blood_type_id'); }
}