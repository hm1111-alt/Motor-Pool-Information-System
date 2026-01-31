<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleTravelHistory extends Model
{
    use HasFactory;
    
    protected $table = 'vehicle_travel_history';
    
    protected $fillable = [
        'trip_ticket_id',
        'vehicle_id',
        'driver_id',
        'head_of_party',
        'destination',
        'departure_date',
        'departure_time',
        'arrival_date',
        'arrival_time',
        'distance_km',
        'remarks',
    ];
    
    protected $casts = [
        'departure_date' => 'date',
        'arrival_date' => 'date',
        'distance_km' => 'decimal:2',
    ];
    
    /**
     * Accessor for departure_time to ensure proper formatting
     */
    public function getDepartureTimeAttribute($value)
    {
        if ($value instanceof \DateTime || $value instanceof \Illuminate\Support\Carbon) {
            return $value;
        }
        
        if (is_string($value) && !empty($value)) {
            return \Illuminate\Support\Carbon::createFromFormat('H:i:s', $value);
        }
        
        return $value;
    }
    
    /**
     * Accessor for arrival_time to ensure proper formatting
     */
    public function getArrivalTimeAttribute($value)
    {
        if ($value instanceof \DateTime || $value instanceof \Illuminate\Support\Carbon) {
            return $value;
        }
        
        if (is_string($value) && !empty($value)) {
            return \Illuminate\Support\Carbon::createFromFormat('H:i:s', $value);
        }
        
        return $value;
    }
    
    /**
     * Get the trip ticket associated with this travel history record.
     */
    public function tripTicket(): BelongsTo
    {
        return $this->belongsTo(TripTicket::class, 'trip_ticket_id');
    }
    
    /**
     * Get the vehicle associated with this travel history record.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
    
    /**
     * Get the driver associated with this travel history record.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}