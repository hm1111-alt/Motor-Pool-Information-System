<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    
}