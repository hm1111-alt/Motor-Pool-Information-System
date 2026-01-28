<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itinerary extends Model
{
    protected $fillable = [
        'travel_order_id',
        'driver_id',
        'vehicle_id',
        'date_from',
        'date_to',
        'destination',
        'purpose',
        'departure_time',
        'status',
        'unit_head_approved',
        'unit_head_approved_by',
        'unit_head_approved_at',
        'vp_approved',
        'vp_approved_by',
        'vp_approved_at'
    ];
    
    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'unit_head_approved' => 'boolean',
        'unit_head_approved_at' => 'datetime',
        'vp_approved' => 'boolean',
        'vp_approved_at' => 'datetime'
        // departure_time is a TIME field in MySQL, no specific cast needed
    ];
    
    /**
     * Get the travel order associated with the itinerary.
     */
    public function travelOrder(): BelongsTo
    {
        return $this->belongsTo(TravelOrder::class, 'travel_order_id');
    }
    
    /**
     * Get the vehicle associated with the itinerary.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
    
    /**
     * Get the driver assigned to the itinerary.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
    
    /**
     * Get the trip tickets associated with the itinerary.
     */
    public function tripTickets()
    {
        return $this->hasMany(TripTicket::class);
    }
}