<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_DRIVER = 'driver';
    const ROLE_EMPLOYEE = 'employees';
    const ROLE_ADMIN = 'admin';
    const ROLE_MOTORPOOL_ADMIN = 'motorpool_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_num',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role checking methods
    public function isMotorpoolAdmin()
    {
        return $this->role === self::ROLE_MOTORPOOL_ADMIN;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isDriver()
    {
        return $this->role === self::ROLE_DRIVER;
    }

    public function isEmployee()
    {
        return $this->role === self::ROLE_EMPLOYEE;
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }
    
    public function isUnitHead()
    {
        // Check if user is a unit head by checking their employee record
        if ($this->employee) {
            return $this->employee->is_head && !$this->employee->is_divisionhead && !$this->employee->is_vp;
        }
        return false;
    }
    
    public function isVp()
    {
        // Check if user is a VP by checking their employee record
        if ($this->employee) {
            return $this->employee->is_vp;
        }
        return false;
    }

    // Relationship methods
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }
}