<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Check office details
try {
    echo "=== Checking Office Details ===\n\n";
    
    // Get president
    $president = Employee::where('is_president', 1)->first();
    echo "President: " . $president->first_name . " " . $president->last_name . " (Office ID: " . $president->office_id . ")\n";
    
    // Get division head
    $divisionHead = Employee::where('is_divisionhead', 1)->first();
    echo "Division Head: " . $divisionHead->first_name . " " . $divisionHead->last_name . " (Office ID: " . $divisionHead->office_id . ")\n";
    
    echo "\nThe president can only approve travel orders from employees in the same office.\n";
    echo "President's office ID: " . $president->office_id . "\n";
    echo "Division Head's office ID: " . $divisionHead->office_id . "\n";
    echo "Match: " . ($president->office_id == $divisionHead->office_id ? "Yes" : "No") . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}