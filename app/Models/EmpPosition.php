<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'position_name',
        'class_id',
        'office_id',
        'division_id',
        'unit_id',
        'subunit_id',
        'is_primary',
        'is_unit_head',
        'is_division_head',
        'is_vp',
        'is_president',
    ];

    protected $casts = [
        'class_id' => 'integer',
        'office_id' => 'integer',
        'division_id' => 'integer',
        'unit_id' => 'integer',
        'subunit_id' => 'integer',
        'is_primary' => 'boolean',
        'is_unit_head' => 'boolean',
        'is_division_head' => 'boolean',
        'is_vp' => 'boolean',
        'is_president' => 'boolean',
    ];

    /**
     * Get the employee that owns this position record.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the office associated with this position.
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the division associated with this position.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the unit associated with this position.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the subunit associated with this position.
     */
    public function subunit()
    {
        return $this->belongsTo(Subunit::class);
    }

    /**
     * Get the class associated with this position.
     */
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
}