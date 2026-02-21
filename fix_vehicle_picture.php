<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$vehicle = \App\Models\Vehicle::find(18);
if ($vehicle) {
    $vehicle->picture = 'vehicles/images/vehicle_default.png';
    $vehicle->save();
    echo "Fixed vehicle 18 picture field\n";
} else {
    echo "Vehicle 18 not found\n";
}