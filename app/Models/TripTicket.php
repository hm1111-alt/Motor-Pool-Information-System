<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\TripTicketObserver;

#[ObservedBy([TripTicketObserver::class])]
class TripTicket extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'itinerary_id',
        'status',
        'passengers',
        'head_of_party',
    ];
    
    protected $casts = [
        'status' => 'string',
        'passengers' => 'array',
        'head_of_party' => 'string',
    ];
    
    /**
     * Get the itinerary associated with the trip ticket.
     */
    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }
    
    /**
     * Get the vehicle travel history records for this trip ticket.
     */
    public function travelHistory()
    {
        return $this->hasMany(VehicleTravelHistory::class, 'trip_ticket_id');
    }
    
}