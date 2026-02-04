<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subunit extends Model
{
    use HasFactory;

    // Specify the correct table name
    protected $table = 'lib_subunits';
    
    // Specify the primary key
    protected $primaryKey = 'id_subunit';
    
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
        'subunit_name',
        'subunit_abbr',
        'unit_id',
        'subunit_isactive',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subunit_isactive' => 'boolean',
        'subunit_updated_date' => 'datetime',
    ];

    /**
     * Get the unit that owns the subunit.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Get the employees for the subunit through positions.
     */
    public function employees()
    {
        return $this->hasManyThrough(Employee::class, EmpPosition::class, 'subunit_id', 'id', 'id_subunit', 'employee_id');
    }

    /**
     * Get the positions for the subunit.
     */
    public function positions()
    {
        return $this->hasMany(EmpPosition::class, 'subunit_id');
    }
}