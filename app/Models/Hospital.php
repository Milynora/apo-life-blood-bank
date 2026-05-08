<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BloodRequest;
use App\Models\User;

class Hospital extends Model
{
    protected $primaryKey = 'hospital_id';
    protected $fillable = ['user_id', 'hospital_name', 'license_number', 'address', 'contact_number'];

    public function user():     \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(User::class); }
    public function requests(): \Illuminate\Database\Eloquent\Relations\HasMany  { return $this->hasMany(BloodRequest::class, 'hospital_id'); }
}