<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'picture',
        'plate_number',
        'model',
        'type',
        'seating_capacity',
        'mileage',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'seating_capacity' => 'integer',
        'mileage' => 'integer',
    ];

    /**
     * Get the itineraries for the vehicle.
     */
    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }
}