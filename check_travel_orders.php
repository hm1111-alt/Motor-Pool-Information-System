<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Check current state of travel orders
try {
    echo "=== Current State of Travel Orders ===\n\n";
    
    // Get all travel orders with employee info
    $travelOrders = TravelOrder::with('employee')->orderBy('id')->get();
    
    foreach ($travelOrders as $travelOrder) {
        echo "Travel Order ID: " . $travelOrder->id . "\n";
        echo "  Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . " (ID: " . $travelOrder->employee->id . ")\n";
        echo "  Is Head: " . ($travelOrder->employee->is_head ? 'Yes' : 'No') . "\n";
        echo "  Office ID: " . $travelOrder->employee->office_id . "\n";
        echo "  Head Approved: " . var_export($travelOrder->head_approved, true) . "\n";
        echo "  Division Head Approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
        echo "  VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
        echo "  Status: " . $travelOrder->status . "\n";
        echo "  Remarks: " . $travelOrder->remarks . "\n";
        
        // Check if this should appear in VP pending list
        if ($travelOrder->employee->is_head) {
            // Head travel order - check if division head approved and VP not yet approved
            if ($travelOrder->divisionhead_approved && is_null($travelOrder->vp_approved)) {
                echo "  → Should appear in VP Pending tab (Head approved by Division Head)\n";
            } else {
                echo "  → Should NOT appear in VP Pending tab\n";
            }
        } else {
            // Regular employee travel order - check if head approved and VP not yet approved
            if ($travelOrder->head_approved && is_null($travelOrder->vp_approved)) {
                echo "  → Should appear in VP Pending tab (Employee approved by Head)\n";
            } else {
                echo "  → Should NOT appear in VP Pending tab\n";
            }
        }
        
        echo "---\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}