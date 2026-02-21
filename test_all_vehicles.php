<?php
// Test script to verify all vehicles are retrieved
require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Vehicle;

// Test the vehicle data query
$allVehicles = Vehicle::orderBy('created_at', 'desc')->get();

echo "Found " . $allVehicles->count() . " total vehicles:\n";
foreach ($allVehicles as $vehicle) {
    echo "- ID: {$vehicle->id}, Plate: {$vehicle->plate_number}, Model: {$vehicle->model}, Status: {$vehicle->status}\n";
}

echo "\nData structure for JSON:\n";
echo json_encode($allVehicles->toArray(), JSON_PRETTY_PRINT);