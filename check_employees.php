<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;

// Check employees
try {
    echo "=== Checking Employees ===\n\n";
    
    // Get division heads
    $divisionHeads = Employee::where('is_divisionhead', 1)->get();
    echo "Found " . count($divisionHeads) . " division heads:\n";
    foreach ($divisionHeads as $dh) {
        echo "- " . $dh->first_name . " " . $dh->last_name . " (ID: " . $dh->id . ", Office ID: " . $dh->office_id . ")\n";
    }
    
    echo "\n";
    
    // Get VPs
    $vps = Employee::where('is_vp', 1)->get();
    echo "Found " . count($vps) . " VPs:\n";
    foreach ($vps as $vp) {
        echo "- " . $vp->first_name . " " . $vp->last_name . " (ID: " . $vp->id . ", Office ID: " . $vp->office_id . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}