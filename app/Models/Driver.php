<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'middle_initial',
        'ext_name',
        'full_name',
        'full_name2',
        'sex',
        'prefix',
        'availability_status',
    ];

    /**
     * Get the user record for this driver.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full name of the driver.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the formal name of the driver (with prefix).
     */
    public function getFormalNameAttribute()
    {
        return $this->prefix ? $this->prefix . ' ' . $this->first_name . ' ' . $this->last_name : $this->first_name . ' ' . $this->last_name;
    }
}