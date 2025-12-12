<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Test to check if employee relationship and is_president attribute are working correctly
try {
    echo "=== Testing Employee President Check ===\n\n";
    
    // Get a president employee
    $president = Employee::where('is_president', 1)->first();
    
    if (!$president) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $president->first_name . " " . $president->last_name . " (ID: " . $president->id . ")\n";
    echo "is_president: " . var_export($president->is_president, true) . "\n";
    
    // Create a travel order for this president
    $travelOrder = TravelOrder::create([
        'employee_id' => $president->id,
        'destination' => 'President Test Destination',
        'date_from' => '2025-12-15',
        'date_to' => '2025-12-16',
        'purpose' => 'President test purpose',
        'status' => 'approved',
        'president_approved' => true,
        'president_approved_at' => now(),
    ]);
    
    echo "\nCreated travel order ID: " . $travelOrder->id . "\n";
    echo "Initial status: " . $travelOrder->status . "\n";
    echo "President approved: " . var_export($travelOrder->president_approved, true) . "\n";
    
    // Load the travel order with the employee relationship
    $travelOrderWithEmployee = TravelOrder::with('employee')->find($travelOrder->id);
    
    echo "\nWith employee relationship loaded:\n";
    echo "Employee ID: " . $travelOrderWithEmployee->employee->id . "\n";
    echo "Employee is_president: " . var_export($travelOrderWithEmployee->employee->is_president, true) . "\n";
    echo "Status: " . $travelOrderWithEmployee->status . "\n";
    echo "Remarks: " . $travelOrderWithEmployee->remarks . "\n";
    
    // Check the remarks calculation
    echo "\nRemarks calculation check:\n";
    echo "employee->is_president && president_approved: " . var_export($travelOrderWithEmployee->employee->is_president && $travelOrderWithEmployee->president_approved, true) . "\n";
    echo "employee->is_president && status === 'approved': " . var_export($travelOrderWithEmployee->employee->is_president && $travelOrderWithEmployee->status === 'approved', true) . "\n";
    
    // Clean up - delete the test travel order
    $travelOrder->delete();
    
    echo "\nTest completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}