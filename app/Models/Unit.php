<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    // Correct table name
    protected $table = 'lib_units';
    
    // ✅ Correct primary key
    protected $primaryKey = 'id';
    
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'unit_name',
        'unit_abbr',
        'unit_code',
        'unit_office',
        'unit_division',
        'unit_isactive',
        'unit_updated_date',
    ];

    protected $casts = [
        'unit_isactive' => 'boolean',
        'unit_updated_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($unit) {
            $unit->unit_updated_date = now();
        });

        static::updating(function ($unit) {
            $unit->unit_updated_date = now();
        });
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'unit_division');
    }

    public function employees()
    {
        return $this->hasManyThrough(Employee::class, EmpPosition::class, 'unit_id', 'id', 'id', 'employee_id');
    }

    public function positions()
    {
        return $this->hasMany(EmpPosition::class, 'unit_id');
    }

    public function subunits()
    {
        return $this->hasMany(Subunit::class, 'unit_id');
    }
}