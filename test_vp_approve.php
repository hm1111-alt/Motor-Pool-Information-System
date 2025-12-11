<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\VpTravelOrderController;

// Test the VP approval logic
try {
    // Get the VP employee (Ravelina Velasco - ID: 9)
    $vpEmployee = Employee::find(9);
    
    if (!$vpEmployee) {
        echo "VP not found\n";
        exit;
    }
    
    echo "VP: " . $vpEmployee->first_name . " " . $vpEmployee->last_name . " (ID: " . $vpEmployee->id . ")\n";
    
    // Get the travel order that should be approvable
    $travelOrder = TravelOrder::find(64);
    
    if (!$travelOrder) {
        echo "Travel order not found\n";
        exit;
    }
    
    echo "Travel Order ID: " . $travelOrder->id . "\n";
    echo "Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . "\n";
    echo "Is Head: " . ($travelOrder->employee->is_head ? 'Yes' : 'No') . "\n";
    echo "Division Head Approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
    echo "VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
    
    // Mock the Auth facade
    \Illuminate\Support\Facades\Auth::shouldReceive('user')->andReturn((object)[
        'employee' => $vpEmployee
    ]);
    
    // Create controller instance
    $controller = new VpTravelOrderController();
    
    // Test the approve method
    echo "\nCalling approve method...\n";
    $response = $controller->approve($travelOrder);
    
    // Refresh the travel order
    $travelOrder->refresh();
    
    echo "After approval:\n";
    echo "Division Head Approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
    echo "VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
    echo "VP Approved At: " . ($travelOrder->vp_approved_at ? $travelOrder->vp_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
    echo "Status: " . $travelOrder->status . "\n";
    echo "Remarks: " . $travelOrder->remarks . "\n";
    
    echo "\nVP approval test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}