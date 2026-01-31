<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Driver extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'firsts_name',
        'middle_initial',
        'last_name',
        'full_name',
        'full_name2',
        'contact_num',
        'email',
        'password',
        'address',
        'position',
        'official_station',
        'availability_status',
    ];
    
    protected $hidden = [
        'password',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the user associated with this driver.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get the itineraries for this driver.
     */
    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class, 'driver_id');
    }
    
    /**
     * Get the full name of the driver.
     */
    public function getFullNameAttribute(): string
    {
        return $this->firsts_name . ' ' . $this->last_name;
    }
    
    /**
     * Get the formal name of the driver (with middle initial).
     */
    public function getFormalNameAttribute(): string
    {
        $fullName = $this->firsts_name;
        if ($this->middle_initial) {
            $fullName .= ' ' . $this->middle_initial . '.';
        }
        $fullName .= ' ' . $this->last_name;
        return $fullName;
    }
    
    /**
     * Hash the password when setting it.
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}