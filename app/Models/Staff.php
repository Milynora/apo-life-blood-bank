<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Donation;
use App\Models\Screening;

class Staff extends Model
{
    protected $primaryKey = 'staff_id';
    protected $fillable = ['user_id'];

// Updated: Pulls name from the related User record
    public function getNameAttribute(): string
{
    return $this->user?->name ?? 'Unknown Staff';
}
    
    public function user():      \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(User::class); }
    public function donations(): \Illuminate\Database\Eloquent\Relations\HasMany  { return $this->hasMany(Donation::class, 'staff_id'); }
    public function screenings(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Screening::class, 'staff_id'); }
}