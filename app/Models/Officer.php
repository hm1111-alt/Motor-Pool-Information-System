<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'unit_head',
        'division_head',
        'vp',
        'president',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_head' => 'boolean',
        'division_head' => 'boolean',
        'vp' => 'boolean',
        'president' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the employee that owns this officer record.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Check if this officer has any role assigned
     */
    public function hasAnyRole()
    {
        return $this->unit_head || $this->division_head || $this->vp || $this->president;
    }

    /**
     * Get the role names as an array
     */
    public function getRoleNames()
    {
        $roles = [];
        if ($this->unit_head) $roles[] = 'Unit Head';
        if ($this->division_head) $roles[] = 'Division Head';
        if ($this->vp) $roles[] = 'VP';
        if ($this->president) $roles[] = 'President';
        return $roles;
    }

    /**
     * Get the primary role name
     */
    public function getPrimaryRole()
    {
        if ($this->president) return 'President';
        if ($this->vp) return 'VP';
        if ($this->division_head) return 'Division Head';
        if ($this->unit_head) return 'Unit Head';
        return 'None';
    }
}