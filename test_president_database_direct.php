<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

// Direct database test to check what's actually being saved
try {
    echo "=== DIRECT DATABASE TEST FOR PRESIDENT TRAVEL ORDERS ===\n\n";
    
    // Get a president employee
    $president = Employee::where('is_president', 1)->first();
    
    if (!$president) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $president->first_name . " " . $president->last_name . " (ID: " . $president->id . ")\n";
    echo "is_president: " . var_export($president->is_president, true) . "\n";
    
    // Create a travel order for this president using the exact same logic as the controller
    echo "\nCreating travel order with controller logic...\n";
    $travelOrder = TravelOrder::create([
        'employee_id' => $president->id,
        'destination' => 'Direct Database Test',
        'date_from' => '2025-12-25',
        'date_to' => '2025-12-27',
        'purpose' => 'Direct database test purpose',
        'status' => 'approved', // President travel orders are automatically approved
        'president_approved' => true, // Mark as approved by president
        'president_approved_at' => now(), // Set approval timestamp
    ]);
    
    echo "Travel order created with ID: " . $travelOrder->id . "\n";
    
    // Check what's actually in the database immediately after creation
    echo "\n--- IMMEDIATE DATABASE CHECK ---\n";
    $dbRecord = DB::table('travel_orders')->where('id', $travelOrder->id)->first();
    echo "Database record status: " . $dbRecord->status . "\n";
    echo "Database record president_approved: " . var_export($dbRecord->president_approved, true) . "\n";
    echo "Database record president_approved_at: " . $dbRecord->president_approved_at . "\n";
    
    // Wait a moment and check again
    echo "\n--- AFTER SHORT DELAY ---\n";
    sleep(2);
    $dbRecord2 = DB::table('travel_orders')->where('id', $travelOrder->id)->first();
    echo "Database record status: " . $dbRecord2->status . "\n";
    echo "Database record president_approved: " . var_export($dbRecord2->president_approved, true) . "\n";
    echo "Database record president_approved_at: " . $dbRecord2->president_approved_at . "\n";
    
    // Check the model
    echo "\n--- MODEL CHECK ---\n";
    $travelOrder->refresh();
    echo "Model status: " . $travelOrder->status . "\n";
    echo "Model president_approved: " . var_export($travelOrder->president_approved, true) . "\n";
    echo "Model president_approved_at: " . ($travelOrder->president_approved_at ? $travelOrder->president_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
    echo "Model remarks: " . $travelOrder->remarks . "\n";
    
    // Check if employee relationship is loaded correctly
    echo "\n--- EMPLOYEE RELATIONSHIP CHECK ---\n";
    $travelOrderWithEmployee = TravelOrder::with('employee')->find($travelOrder->id);
    echo "Employee is_president: " . var_export($travelOrderWithEmployee->employee->is_president, true) . "\n";
    
    // Check remarks calculation
    echo "\n--- REMARKS CALCULATION CHECK ---\n";
    echo "Condition 1 (employee->is_president && president_approved): " . var_export($travelOrderWithEmployee->employee->is_president && $travelOrderWithEmployee->president_approved, true) . "\n";
    echo "Condition 2 (employee->is_president && status === 'approved'): " . var_export($travelOrderWithEmployee->employee->is_president && $travelOrderWithEmployee->status === 'approved', true) . "\n";
    
    // Clean up - delete the test travel order
    $travelOrder->delete();
    
    echo "\nTest completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}