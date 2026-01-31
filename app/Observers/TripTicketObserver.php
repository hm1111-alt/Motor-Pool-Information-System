<?php

namespace App\Observers;

use App\Models\TripTicket;
use App\Models\VehicleTravelHistory;
use App\Services\DistanceEstimationService;

class TripTicketObserver
{
    /**
     * Handle the TripTicket "updated" event.
     */
    public function updated(TripTicket $tripTicket): void
    {
        // Check if the status was changed to 'Completed'
        if ($tripTicket->isDirty('status') && $tripTicket->status === 'Completed') {
            $this->createVehicleTravelHistory($tripTicket);
        }
    }
    
    /**
     * Handle the TripTicket "created" event.
     * This handles cases where a trip ticket is created directly with 'Completed' status
     */
    public function created(TripTicket $tripTicket): void
    {
        if ($tripTicket->status === 'Completed') {
            $this->createVehicleTravelHistory($tripTicket);
        }
    }
    
    /**
     * Create vehicle travel history record from completed trip ticket
     */
    private function createVehicleTravelHistory(TripTicket $tripTicket): void
    {
        // Load the itinerary with related models
        $tripTicket->load(['itinerary.vehicle', 'itinerary.driver', 'itinerary.travelOrder.employee']);
        
        // Check if itinerary exists
        if (!$tripTicket->itinerary) {
            return;
        }
        
        // Check if a travel history record already exists for this trip ticket
        $existingRecord = VehicleTravelHistory::where('trip_ticket_id', $tripTicket->id)->first();
        if ($existingRecord) {
            return; // Prevent duplicate records
        }
        
        // Prepare data for vehicle travel history
        $data = [
            'trip_ticket_id' => $tripTicket->id,
            'vehicle_id' => $tripTicket->itinerary->vehicle_id,
            'driver_id' => $tripTicket->itinerary->driver_id,
            'head_of_party' => $tripTicket->head_of_party,
            'destination' => $tripTicket->itinerary->destination ?? 'N/A',
            'departure_date' => $tripTicket->itinerary->date_from ?? now(),
            'departure_time' => $tripTicket->itinerary->departure_time,
            'arrival_date' => $tripTicket->itinerary->date_to,
            'arrival_time' => $tripTicket->itinerary->departure_time, // Using departure time as default
            'distance_km' => $this->estimateDistanceFromCLSU($tripTicket->itinerary->destination ?? ''),
            'remarks' => 'Auto-generated from completed trip ticket',
        ];
        
        // Create the vehicle travel history record
        VehicleTravelHistory::create($data);
    }
    
    /**
     * Estimate round-trip distance from CLSU to destination using Google Maps API
     */
    private function estimateDistanceFromCLSU(string $destination): float|null
    {
        if (empty($destination)) {
            return null;
        }
        
        // Use OSRM/OpenStreetMap for accurate distance calculation
        $distanceService = new DistanceEstimationService();
        
        // CLSU is located in Science City of Muñoz, Nueva Ecija, Philippines
        $origin = 'Central Luzon State University, Science City of Muñoz, Nueva Ecija, Philippines';
        
        // Get round-trip distance (OSRM service handles the calculation)
        $roundTripDistance = $distanceService->getRoundTripDistance($origin, $destination);
        
        return $roundTripDistance;
    }
}