<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'division_name',
        'division_abbr',
        'office_id',
        'division_code',
        'division_isactive',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'division_isactive' => 'boolean',
    ];

    /**
     * Get the office that owns the division.
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the employees for the division.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get the units for the division.
     */
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}