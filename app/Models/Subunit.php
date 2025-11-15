<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subunit extends Model
{
    use HasFactory;

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
    ];

    /**
     * Get the unit that owns the subunit.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the employees for the subunit.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}