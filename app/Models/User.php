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

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN || $this->role === self::ROLE_MOTORPOOL_ADMIN;
    }

    /**
     * Check if user is a motorpool admin
     */
    public function isMotorpoolAdmin()
    {
        return $this->role === self::ROLE_MOTORPOOL_ADMIN;
    }

    /**
     * Check if user is a driver
     */
    public function isDriver()
    {
        return $this->role === self::ROLE_DRIVER;
    }

    /**
     * Check if user is an employee
     */
    public function isEmployee()
    {
        return $this->role === self::ROLE_EMPLOYEE;
    }
}