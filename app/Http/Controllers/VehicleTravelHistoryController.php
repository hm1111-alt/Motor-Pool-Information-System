<?php

namespace App\Http\Controllers;

use App\Models\TripTicket;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleTravelHistoryController extends Controller
{
    /**
     * Display vehicle travel history
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $vehicleId = $request->get('vehicle_id');
        
        // Get all vehicles for the filter dropdown
        $vehicles = Vehicle::orderBy('plate_number')->get();
        
        // Build the query for trip tickets with completed status
        $query = TripTicket::with([
            'itinerary.driver',
            'itinerary.vehicle',
            'itinerary.travelOrder.employee'
        ])
        ->where('status', 'Completed')
        ->orderBy('created_at', 'desc');
        
        // Filter by vehicle if selected
        if ($vehicleId) {
            $query->whereHas('itinerary', function ($subQuery) use ($vehicleId) {
                $subQuery->where('vehicle_id', $vehicleId);
            });
        }
        
        // Apply search filter
        if ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->whereHas('itinerary', function ($itineraryQuery) use ($search) {
                    $itineraryQuery->where('destination', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('itinerary.driver', function ($driverQuery) use ($search) {
                    $driverQuery->where('first_name', 'LIKE', "%{$search}%")
                               ->orWhere('last_name', 'LIKE', "%{$search}%");
                })
                ->orWhere('head_of_party', 'LIKE', "%{$search}%");
            });
        }
        
        $tripTickets = $query->paginate(10)->appends($request->except('page'));
        
        // Calculate distances for each trip ticket
        foreach ($tripTickets as $tripTicket) {
            $tripTicket->estimated_distance = $this->estimateDistanceFromCLSU($tripTicket->itinerary->destination ?? '');
        }
        
        return view('vehicles.travel-history', compact('tripTickets', 'vehicles', 'vehicleId', 'search'));
    }
    
    /**
     * Estimate distance from CLSU to destination
     * This is a simplified estimation - in a real application, you would use
     * Google Maps API or similar service for accurate distance calculation
     */
    private function estimateDistanceFromCLSU(string $destination): string
    {
        if (empty($destination)) {
            return 'N/A';
        }
        
        // Simplified distance estimation based on common destinations
        // In a real application, you would integrate with a mapping API
        $destination = strtolower(trim($destination));
        
        // Common destinations from CLSU (Science City of Muñoz, Nueva Ecija)
        $distanceMap = [
            // Local destinations
            'munoz' => '5',
            'science city' => '0',
            'clsu' => '0',
            'central luzon state university' => '0',
            'campus' => '2',
            
            // Nearby municipalities
            'cabanatuan' => '45',
            'cabanatuan city' => '45',
            'gapan' => '35',
            'gapan city' => '35',
            'san jose' => '25',
            'san jose city' => '25',
            'quezon' => '30',
            'quezon city' => '60',
            
            // Provinces
            'pampanga' => '80',
            'tarlac' => '90',
            'bulacan' => '120',
            'nueva ecija' => '40',
            
            // Major cities
            'manila' => '160',
            'metro manila' => '160',
            'quezon city' => '150',
            'caloocan' => '155',
            'makati' => '165',
            'pasay' => '162',
            
            // Airports
            'naia' => '160',
            'ninoy aquino' => '160',
            'clark' => '70',
            'clark international' => '70',
        ];
        
        // Check for exact matches
        foreach ($distanceMap as $key => $distance) {
            if (strpos($destination, $key) !== false) {
                return $distance . ' km';
            }
        }
        
        // Default estimation for unknown destinations
        return 'N/A';
    }
}