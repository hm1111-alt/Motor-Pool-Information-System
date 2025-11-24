<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'destination',
        'purpose',
        'date_from',
        'date_to',
        'departure_time',
        'arrival_time',
        'status',
        'divisionhead_approved',
        'divisionhead_approved_at',
        'vp_approved',
        'vp_approved_at',
        'divisionhead_declined',
        'divisionhead_declined_at',
        'vp_declined',
        'vp_declined_at',
        'head_approved',
        'head_disapproved',
        'head_approved_at',
        'head_disapproved_at',
        'president_approved',
        'president_declined',
        'president_approved_at',
        'president_declined_at',
        'president_approved_by',
        'president_declined_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'divisionhead_approved' => 'boolean',
        'vp_approved' => 'boolean',
        'divisionhead_declined' => 'boolean',
        'vp_declined' => 'boolean',
        'head_approved' => 'boolean',
        'head_disapproved' => 'boolean',
        'president_approved' => 'boolean',
        'president_declined' => 'boolean',
        'divisionhead_approved_at' => 'datetime',
        'vp_approved_at' => 'datetime',
        'divisionhead_declined_at' => 'datetime',
        'vp_declined_at' => 'datetime',
        'head_approved_at' => 'datetime',
        'head_disapproved_at' => 'datetime',
        'president_approved_at' => 'datetime',
        'president_declined_at' => 'datetime',
    ];

    /**
     * Get the employee that owns the travel order.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the itineraries for the travel order.
     */
    public function itineraries()
    {
        return $this->hasMany(Itinerary::class, 'travel_orders_id');
    }
}