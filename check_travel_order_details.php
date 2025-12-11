<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Check travel order details
try {
    echo "=== Checking Travel Order Details ===\n\n";
    
    // Get travel order ID 68
    $travelOrder = TravelOrder::find(68);
    
    if (!$travelOrder) {
        echo "Travel order not found\n";
        exit;
    }
    
    echo "Travel Order ID: " . $travelOrder->id . "\n";
    echo "Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . "\n";
    echo "Is Head: " . ($travelOrder->employee->is_head ? 'Yes' : 'No') . "\n";
    echo "Is Division Head: " . ($travelOrder->employee->is_divisionhead ? 'Yes' : 'No') . "\n";
    echo "Office ID: " . $travelOrder->employee->office_id . "\n";
    echo "VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
    echo "President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
    echo "Remarks: " . $travelOrder->remarks . "\n";
    echo "Status: " . $travelOrder->status . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}