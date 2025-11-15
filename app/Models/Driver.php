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
        'first_name',
        'last_name',
        'middle_initial',
        'ext_name',
        'full_name',
        'full_name2',
        'sex',
        'contact_number',
        'position',
        'official_station',
        'availability_status',
    ];

    /**
     * Get the full name of the driver.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the last name, first name format of the driver.
     */
    public function getFullName2Attribute()
    {
        return $this->last_name . ', ' . $this->first_name;
    }
}