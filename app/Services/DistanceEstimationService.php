<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class DistanceEstimationService
{
    private Client $client;
    private string $osrmBaseUrl = 'https://router.project-osrm.org';
    private string $nominatimBaseUrl = 'https://nominatim.openstreetmap.org';
    
    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'headers' => [
                'User-Agent' => 'Motorpool-System/1.0'
            ]
        ]);
    }
    
    /**
     * Get round-trip distance between two locations using OSRM
     * 
     * @param string $origin Starting location
     * @param string $destination Ending location
     * @return float|null Distance in kilometers (round-trip), null if API fails
     */
    public function getRoundTripDistance(string $origin, string $destination): ?float
    {
        try {
            // Get coordinates for both locations
            $originCoords = $this->geocodeLocation($origin);
            $destCoords = $this->geocodeLocation($destination);
            
            if (!$originCoords || !$destCoords) {
                return $this->getFallbackDistance($destination);
            }
            
            // Get one-way distance using OSRM
            $oneWayDistance = $this->getOSRMDistance($originCoords, $destCoords);
            
            if ($oneWayDistance === null) {
                return $this->getFallbackDistance($destination);
            }
            
            // Return round-trip distance (multiply by 2)
            return round($oneWayDistance * 2, 2);
            
        } catch (\Exception $e) {
            Log::error('Distance API Error: ' . $e->getMessage());
            return $this->getFallbackDistance($destination);
        }
    }
    
    /**
     * Geocode a location to coordinates using Nominatim
     */
    private function geocodeLocation(string $location): ?array
    {
        try {
            $response = $this->client->get("{$this->nominatimBaseUrl}/search", [
                'query' => [
                    'q' => $location,
                    'format' => 'json',
                    'limit' => 1
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if (empty($data)) {
                return null;
            }
            
            return [
                'lat' => (float)$data[0]['lat'],
                'lon' => (float)$data[0]['lon']
            ];
            
        } catch (\Exception $e) {
            Log::warning('Geocoding failed for: ' . $location . ' - ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get distance using OSRM routing engine
     */
    private function getOSRMDistance(array $origin, array $destination): ?float
    {
        try {
            $coordinates = "{$origin['lon']},{$origin['lat']};{$destination['lon']},{$destination['lat']}";
            
            $response = $this->client->get("{$this->osrmBaseUrl}/route/v1/driving/{$coordinates}", [
                'query' => [
                    'overview' => 'false',
                    'steps' => 'false'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if ($data['code'] !== 'Ok' || empty($data['routes'])) {
                return null;
            }
            
            // Distance is in meters, convert to kilometers
            $distanceInKm = $data['routes'][0]['distance'] / 1000;
            
            return round($distanceInKm, 2);
            
        } catch (\Exception $e) {
            Log::warning('OSRM routing failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Fallback distance estimation for common destinations from CLSU
     * Used when API calls fail
     */
    private function getFallbackDistance(string $destination): ?float
    {
        $destination = strtolower(trim($destination));
        
        // Common destinations from CLSU (Science City of Muñoz, Nueva Ecija)
        $distanceMap = [
            // Local destinations
            'munoz' => 5,
            'science city' => 0,
            'clsu' => 0,
            'central luzon state university' => 0,
            'campus' => 2,
            
            // Nearby municipalities
            'cabanatuan' => 45,
            'cabanatuan city' => 45,
            'gapan' => 35,
            'gapan city' => 35,
            'san jose' => 25,
            'san jose city' => 25,
            'quezon' => 30,
            'quezon city' => 60,
            
            // Provinces
            'pampanga' => 80,
            'tarlac' => 90,
            'bulacan' => 120,
            'nueva ecija' => 40,
            
            // Major cities
            'manila' => 160,
            'metro manila' => 160,
            'quezon city' => 150,
            'caloocan' => 155,
            'makati' => 165,
            'pasay' => 162,
            
            // Airports
            'naia' => 160,
            'ninoy aquino' => 160,
            'clark' => 70,
            'clark international' => 70,
        ];
        
        // Check for exact matches
        foreach ($distanceMap as $key => $distance) {
            if (strpos($destination, $key) !== false) {
                // Return round-trip distance (multiply by 2)
                return $distance * 2;
            }
        }
        
        // Default for unknown destinations
        return null;
    }
    
    /**
     * Get one-way distance between two locations
     */
    public function getOneWayDistance(string $origin, string $destination): ?float
    {
        try {
            // Get coordinates for both locations
            $originCoords = $this->geocodeLocation($origin);
            $destCoords = $this->geocodeLocation($destination);
            
            if (!$originCoords || !$destCoords) {
                // Fallback to half of round-trip fallback distance
                $fallback = $this->getFallbackDistance($destination);
                return $fallback ? $fallback / 2 : null;
            }
            
            // Get one-way distance using OSRM
            $distance = $this->getOSRMDistance($originCoords, $destCoords);
            
            return $distance;
            
        } catch (\Exception $e) {
            Log::error('One-way distance API Error: ' . $e->getMessage());
            // Fallback to half of round-trip fallback distance
            $fallback = $this->getFallbackDistance($destination);
            return $fallback ? $fallback / 2 : null;
        }
    }
}