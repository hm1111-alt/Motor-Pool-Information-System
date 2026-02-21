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
        'fuel_type',
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
    
    /**
     * Get the travel history records for the vehicle.
     */
    public function travelHistory()
    {
        return $this->hasMany(VehicleTravelHistory::class, 'vehicle_id');
    }
    
    /**
     * Get the maintenance records for the vehicle.
     */
    public function maintenanceRecords()
    {
        return $this->hasMany(VehicleMaintenance::class, 'vehicle_id');
    }
    
    /**
     * Check if the vehicle needs maintenance based on mileage
     * Maintenance is needed every 5000 km
     */
    public function needsMaintenance(): bool
    {
        if ($this->mileage <= 0) {
            return false;
        }
        
        // Calculate the last maintenance mileage
        $lastMaintenanceRecord = $this->maintenanceRecords()->orderBy('created_at', 'desc')->first();
        $lastMaintenanceMileage = $lastMaintenanceRecord ? ($lastMaintenanceRecord->odometer_reading ?? $this->mileage) : 0;
        
        // Check if the difference between current mileage and last maintenance exceeds 5000 km
        return ($this->mileage - $lastMaintenanceMileage) >= 5000;
    }
    
    /**
     * Get the next maintenance due mileage
     */
    public function getNextMaintenanceDue(): int
    {
        $lastMaintenanceRecord = $this->maintenanceRecords()->orderBy('created_at', 'desc')->first();
        $lastMaintenanceMileage = $lastMaintenanceRecord ? ($lastMaintenanceRecord->odometer_reading ?? 0) : 0;
        
        // Return the next milestone after the last maintenance
        return floor(($lastMaintenanceMileage + 5000) / 5000) * 5000;
    }
    
    /**
     * Get the remaining mileage before next maintenance
     */
    public function getRemainingMileageForMaintenance(): int
    {
        $nextMaintenance = $this->getNextMaintenanceDue();
        $remaining = $nextMaintenance - $this->mileage;
        return max(0, $remaining);
    }
    
    /**
     * Get the picture URL for the vehicle
     */
    public function getPictureUrl(): string
    {
        if ($this->picture) {
            // Check if it's a direct public path
            if (str_starts_with($this->picture, 'vehicles/')) {
                return asset($this->picture);
            }
            // Otherwise assume it's in storage
            return asset('storage/' . $this->picture);
        }
        
        // Return default image if no picture
        return asset('vehicles/images/vehicle_default.png');
    }
}