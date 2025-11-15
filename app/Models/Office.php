<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_program',
        'office_name',
        'office_abbr',
        'officer_code',
        'office_isactive',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'office_isactive' => 'boolean',
    ];

    /**
     * Get the employees for the office.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get the divisions for the office.
     */
    public function divisions()
    {
        return $this->hasMany(Division::class);
    }
}