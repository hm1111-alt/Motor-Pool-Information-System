<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    // Specify the correct table name
    protected $table = 'lib_divisions';
    
    // Specify the primary key
    protected $primaryKey = 'id_division';
    
    // Disable auto-incrementing if needed
    public $incrementing = true;
    
    // Specify the key type
    protected $keyType = 'int';

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
        'updated_date' => 'datetime',
    ];

    /**
     * Get the office that owns the division.
     */
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    /**
     * Get the employees for the division through positions.
     */
    public function employees()
    {
        return $this->hasManyThrough(Employee::class, EmpPosition::class, 'division_id', 'id', 'id_division', 'employee_id');
    }

    /**
     * Get the positions for the division.
     */
    public function positions()
    {
        return $this->hasMany(EmpPosition::class, 'division_id');
    }

    /**
     * Get the units for the division.
     */
    public function units()
    {
        return $this->hasMany(Unit::class, 'unit_division');
    }
}