<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\MotorpoolAdminController;
use Illuminate\Http\Request;

// Test that president travel orders appear in motorpool dashboard
try {
    echo "=== Testing President to Motorpool Integration ===\n\n";
    
    // Get a president employee
    $presidentEmployee = Employee::where('is_president', 1)->first();
    
    if (!$presidentEmployee) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $presidentEmployee->first_name . " " . $presidentEmployee->last_name . " (ID: " . $presidentEmployee->id . ")\n";
    
    // Check if president has any approved travel orders
    $presidentApprovedTravelOrders = TravelOrder::where('employee_id', $presidentEmployee->id)
        ->where('status', 'approved')
        ->where('president_approved', true)
        ->get();
    
    echo "\n1. President's approved travel orders:\n";
    echo "Found " . count($presidentApprovedTravelOrders) . " approved travel orders\n";
    
    foreach ($presidentApprovedTravelOrders as $travelOrder) {
        echo "  ✓ Travel Order ID: " . $travelOrder->id . " (Status: " . $travelOrder->status . ", Remarks: " . $travelOrder->remarks . ")\n";
        echo "    President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
        echo "    President Approved At: " . ($travelOrder->president_approved_at ? $travelOrder->president_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
    }
    
    // Test Motorpool Admin Controller
    echo "\n2. Testing Motorpool Admin Approved Travel Orders:\n";
    
    // Create controller instance
    $controller = new MotorpoolAdminController();
    
    // Get approved travel orders from motorpool admin
    $response = $controller->approvedTravelOrders();
    $motorpoolTravelOrders = $response->getData()['travelOrders'];
    
    echo "Found " . count($motorpoolTravelOrders) . " total approved travel orders in motorpool dashboard\n";
    
    // Count president travel orders in motorpool dashboard
    $presidentOrdersInMotorpool = 0;
    foreach ($motorpoolTravelOrders as $travelOrder) {
        if ($travelOrder->employee->is_president) {
            $presidentOrdersInMotorpool++;
        }
    }
    
    echo "Found " . $presidentOrdersInMotorpool . " president travel orders in motorpool dashboard\n";
    
    if ($presidentOrdersInMotorpool > 0) {
        echo "\n✓ President travel orders are correctly displayed in motorpool dashboard\n";
    } else {
        echo "\n✗ President travel orders are NOT displayed in motorpool dashboard\n";
    }
    
    // Show some examples
    echo "\n3. Sample travel orders in motorpool dashboard:\n";
    $count = 0;
    foreach ($motorpoolTravelOrders as $travelOrder) {
        if ($count >= 5) break; // Limit output
        
        echo "  ✓ ID: " . $travelOrder->id . " | Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . " | Type: ";
        
        if ($travelOrder->employee->is_president) {
            echo "President";
        } elseif ($travelOrder->employee->is_vp) {
            echo "VP";
        } elseif ($travelOrder->employee->is_divisionhead) {
            echo "Division Head";
        } elseif ($travelOrder->employee->is_head) {
            echo "Head";
        } else {
            echo "Regular Employee";
        }
        
        echo " | Status: " . $travelOrder->status . " | Remarks: " . $travelOrder->remarks . "\n";
        $count++;
    }
    
    echo "\n=== President to Motorpool Integration Test Completed ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}