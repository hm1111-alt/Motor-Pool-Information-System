<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenance extends Model
{
    use HasFactory;
    
    protected $table = 'vehicle_maintenance';
    
    protected $fillable = [
        'vehicle_id',
        'odometer_reading',
        'date_started',
        'make_or_type',
        'person_office_unit',
        'place',
        'nature_of_work',
        'materials_parts',
        'mechanic_assigned',
        'date_completed',
        'conforme',
        'status',
    ];
    
    protected $casts = [
        'date_started' => 'date',
        'date_completed' => 'date',
        'status' => 'string',
    ];
    
    /**
     * Get the vehicle associated with this maintenance record.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}