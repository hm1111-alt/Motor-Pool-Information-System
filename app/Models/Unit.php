<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unit_name',
        'unit_abbr',
        'unit_code',
        'division_id',
        'unit_isactive',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_isactive' => 'boolean',
    ];

    /**
     * Get the division that owns the unit.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the employees for the unit through positions.
     */
    public function employees()
    {
        return $this->hasManyThrough(Employee::class, EmpPosition::class, 'unit_id', 'id', 'id', 'employee_id');
    }

    /**
     * Get the positions for the unit.
     */
    public function positions()
    {
        return $this->hasMany(EmpPosition::class, 'unit_id');
    }

    /**
     * Get the subunits for the unit.
     */
    public function subunits()
    {
        return $this->hasMany(Subunit::class);
    }
}