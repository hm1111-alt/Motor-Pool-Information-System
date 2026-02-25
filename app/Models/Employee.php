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
        'position_name',
        'contact_num',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'emp_status' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the employee record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all positions for this employee.
     */
    public function positions()
    {
        return $this->hasMany(EmpPosition::class, 'employee_id');
    }

    /**
     * Get the primary position information for this employee.
     */
    public function position()
    {
        return $this->hasOne(EmpPosition::class, 'employee_id')->where('is_primary', true);
    }

    /**
     * Get the class associated with the employee through primary position.
     */
    public function getClassIdAttribute()
    {
        $primaryPosition = $this->positions()->where('is_primary', true)->first();
        return $primaryPosition ? $primaryPosition->class_id : null;
    }

    /**
     * Get the position name associated with the employee through primary position.
     */
    public function getPositionNameAttribute()
    {
        $primaryPosition = $this->positions()->where('is_primary', true)->first();
        return $primaryPosition ? $primaryPosition->position_name : null;
    }

    /**
     * Get the office associated with the employee through primary position.
     */
    public function getOfficeIdAttribute()
    {
        $primaryPosition = $this->positions()->where('is_primary', true)->first();
        return $primaryPosition ? $primaryPosition->office_id : null;
    }

    /**
     * Get the division associated with the employee through primary position.
     */
    public function getDivisionIdAttribute()
    {
        $primaryPosition = $this->positions()->where('is_primary', true)->first();
        return $primaryPosition ? $primaryPosition->division_id : null;
    }

    /**
     * Get the unit associated with the employee through primary position.
     */
    public function getUnitIdAttribute()
    {
        $primaryPosition = $this->positions()->where('is_primary', true)->first();
        return $primaryPosition ? $primaryPosition->unit_id : null;
    }

    /**
     * Get the subunit associated with the employee through primary position.
     */
    public function getSubunitIdAttribute()
    {
        $primaryPosition = $this->positions()->where('is_primary', true)->first();
        return $primaryPosition ? $primaryPosition->subunit_id : null;
    }

    /**
     * Get the office associated with the employee.
     */
    public function office()
    {
        return $this->hasOneThrough(Office::class, EmpPosition::class, 'employee_id', 'id', 'id', 'office_id');
    }

    /**
     * Get the division associated with the employee.
     */
    public function division()
    {
        return $this->hasOneThrough(Division::class, EmpPosition::class, 'employee_id', 'id_division', 'id', 'division_id');
    }

    /**
     * Get the unit associated with the employee.
     */
    public function unit()
    {
        return $this->hasOneThrough(Unit::class, EmpPosition::class, 'employee_id', 'id', 'id', 'unit_id');
    }

    /**
     * Get the subunit associated with the employee.
     */
    public function subunit()
    {
        return $this->hasOneThrough(Subunit::class, EmpPosition::class, 'employee_id', 'id_subunit', 'id', 'subunit_id');
    }

    /**
     * Get the class associated with the employee.
     */
    public function class()
    {
        return $this->hasOneThrough(ClassModel::class, EmpPosition::class, 'employee_id', 'id_class', 'id', 'class_id');
    }

    /**
     * Get the driver record for this employee.
     */
    public function driver()
    {
        return $this->hasOne(Driver::class, 'emp_id');
    }

    /**
     * Get the officer record for this employee.
     */
    public function officer()
    {
        return $this->hasOne(Officer::class, 'employee_id');
    }


    /**
     * Check if employee is a unit head (using new role-per-position system with fallback)
     */
    public function getIsHeadAttribute()
    {
        // First check the new role-per-position system
        $primaryPosition = $this->positions()->where('is_primary', true)->first();
        if ($primaryPosition && $primaryPosition->is_unit_head) {
            return true;
        }
        // Fallback to old system for compatibility
        return $this->officer ? $this->officer->unit_head : false;
    }

    /**
     * Check if employee is a division head (using new role-per-position system with fallback)
     */
    public function getIsDivisionheadAttribute()
    {
        // First check the new role-per-position system
        $primaryPosition = $this->positions()->where('is_primary', true)->first();
        if ($primaryPosition && $primaryPosition->is_division_head) {
            return true;
        }
        // Fallback to old system for compatibility
        return $this->officer ? $this->officer->division_head : false;
    }

    /**
     * Check if employee is a VP (using new role-per-position system with fallback)
     */
    public function getIsVpAttribute()
    {
        // First check the new role-per-position system
        $primaryPosition = $this->positions()->where('is_primary', true)->first();
        if ($primaryPosition && $primaryPosition->is_vp) {
            return true;
        }
        // Fallback to old system for compatibility
        return $this->officer ? $this->officer->vp : false;
    }

    /**
     * Check if employee is a President (using new role-per-position system with fallback)
     */
    public function getIsPresidentAttribute()
    {
        // First check the new role-per-position system
        $primaryPosition = $this->positions()->where('is_primary', true)->first();
        if ($primaryPosition && $primaryPosition->is_president) {
            return true;
        }
        // Fallback to old system for compatibility
        return $this->officer ? $this->officer->president : false;
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
    
    /**
     * Get the travel orders for the employee.
     */
    public function travelOrders()
    {
        return $this->hasMany(TravelOrder::class);
    }
}