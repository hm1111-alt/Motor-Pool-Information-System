<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Vehicle;

$vehicles = Vehicle::all();

echo "Total vehicles: " . $vehicles->count() . "\n\n";

foreach ($vehicles as $vehicle) {
    echo "Plate: " . $vehicle->plate_number . "\n";
    echo "Model: " . $vehicle->model . "\n";
    echo "Type: " . $vehicle->type . "\n";
    echo "Status: " . $vehicle->status . "\n";
    echo "---\n";
}