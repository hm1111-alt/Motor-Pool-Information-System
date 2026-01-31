<?php

namespace App\Observers;

use App\Models\VehicleMaintenance;
use App\Models\Vehicle;

class VehicleMaintenanceObserver
{
    /**
     * Handle the VehicleMaintenance "created" event.
     */
    public function created(VehicleMaintenance $vehicleMaintenance): void
    {
        // Update vehicle status to 'Under Maintenance' when maintenance is created with Pending status
        if ($vehicleMaintenance->status === 'Pending') {
            $vehicle = $vehicleMaintenance->vehicle;
            if ($vehicle) {
                $vehicle->status = 'Under Maintenance';
                $vehicle->save();
            }
        }
    }

    /**
     * Handle the VehicleMaintenance "updated" event.
     */
    public function updated(VehicleMaintenance $vehicleMaintenance): void
    {
        // Update vehicle status based on maintenance status
        $vehicle = $vehicleMaintenance->vehicle;
        if ($vehicle) {
            if ($vehicleMaintenance->status === 'Completed') {
                // When maintenance is completed, we can change status back to Available
                // But only if no other maintenance is pending/ongoing
                $hasActiveMaintenance = $vehicle->maintenanceRecords()
                    ->whereIn('status', ['Pending', 'Ongoing'])
                    ->exists();
                
                if (!$hasActiveMaintenance) {
                    $vehicle->status = 'Available';
                }
            } elseif ($vehicleMaintenance->status === 'Ongoing') {
                $vehicle->status = 'Under Maintenance';
            } elseif ($vehicleMaintenance->status === 'Pending') {
                $vehicle->status = 'Under Maintenance';
            }
            
            $vehicle->save();
        }
    }

    /**
     * Handle the VehicleMaintenance "deleted" event.
     */
    public function deleted(VehicleMaintenance $vehicleMaintenance): void
    {
        // Update vehicle status when maintenance record is deleted
        $vehicle = $vehicleMaintenance->vehicle;
        if ($vehicle) {
            // Check if there are any other active maintenance records
            $hasActiveMaintenance = $vehicle->maintenanceRecords()
                ->whereIn('status', ['Pending', 'Ongoing'])
                ->exists();
            
            if (!$hasActiveMaintenance) {
                $vehicle->status = 'Available';
                $vehicle->save();
            }
        }
    }
}