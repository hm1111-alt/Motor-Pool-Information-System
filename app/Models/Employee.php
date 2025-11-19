<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Employee extends Model
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
        'emp_status',
        'class_id',
        'position_name',
        'office_id',
        'division_id',
        'unit_id',
        'subunit_id',
        'is_head',
        'is_divisionhead',
        'is_vp',
        'is_president',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_head' => 'boolean',
        'is_divisionhead' => 'boolean',
        'is_vp' => 'boolean',
        'is_president' => 'boolean',
        'emp_status' => 'integer',
        'class_id' => 'integer',
        'office_id' => 'integer',
        'division_id' => 'integer',
        'unit_id' => 'integer',
        'subunit_id' => 'integer',
    ];

    /**
     * Get the user that owns the employee record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the class associated with the employee.
     */
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get the office associated with the employee.
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the division associated with the employee.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the unit associated with the employee.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the subunit associated with the employee.
     */
    public function subunit()
    {
        return $this->belongsTo(Subunit::class);
    }

    /**
     * Get the full name of the employee.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the formal name of the employee (with prefix).
     */
    public function getFormalNameAttribute()
    {
        return $this->prefix ? $this->prefix . ' ' . $this->first_name . ' ' . $this->last_name : $this->first_name . ' ' . $this->last_name;
    }
}