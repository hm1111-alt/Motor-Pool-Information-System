<?php
// Test script to verify PDF generation data
require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Vehicle;

// Test the vehicle data query
$activeVehicles = Vehicle::whereIn('status', ['Active', 'Under Maintenance'])
    ->orderBy('created_at', 'desc')
    ->get();

echo "Found " . $activeVehicles->count() . " active/under maintenance vehicles:\n";
foreach ($activeVehicles as $vehicle) {
    echo "- Plate: {$vehicle->plate_number}, Model: {$vehicle->model}, Type: {$vehicle->type}, Status: {$vehicle->status}\n";
}

echo "\nData structure for JSON:\n";
echo json_encode($activeVehicles->toArray(), JSON_PRETTY_PRINT);