<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Debug script to see exactly what happens when creating a President travel order
try {
    echo "=== DEBUGGING PRESIDENT TRAVEL ORDER CREATION ===\n\n";
    
    // Get a president employee
    $president = Employee::where('is_president', 1)->first();
    
    if (!$president) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $president->first_name . " " . $president->last_name . " (ID: " . $president->id . ")\n";
    echo "is_president: " . var_export($president->is_president, true) . "\n";
    
    // Enable query logging
    DB::enableQueryLog();
    
    // Create a travel order with the exact same data as the controller
    echo "\nCreating travel order...\n";
    $travelOrder = TravelOrder::create([
        'employee_id' => $president->id,
        'destination' => 'Debug Test',
        'date_from' => '2026-01-10',
        'date_to' => '2026-01-12',
        'purpose' => 'Debug test purpose',
        'status' => 'approved', // President travel orders are automatically approved
        'president_approved' => true, // Mark as approved by president
        'president_approved_at' => now(), // Set approval timestamp
    ]);
    
    echo "Travel order created with ID: " . $travelOrder->id . "\n";
    
    // Show the queries that were executed
    echo "\n--- QUERIES EXECUTED ---\n";
    $queries = DB::getQueryLog();
    foreach ($queries as $query) {
        echo "SQL: " . $query['query'] . "\n";
        echo "Bindings: " . json_encode($query['bindings']) . "\n";
        echo "Time: " . $query['time'] . "ms\n\n";
    }
    
    // Check what's in the database immediately
    echo "--- DATABASE CHECK ---\n";
    $dbRecord = DB::table('travel_orders')->where('id', $travelOrder->id)->first();
    echo "Database record:\n";
    echo "  ID: " . $dbRecord->id . "\n";
    echo "  Employee ID: " . $dbRecord->employee_id . "\n";
    echo "  Status: " . $dbRecord->status . "\n";
    echo "  President Approved: " . var_export($dbRecord->president_approved, true) . "\n";
    echo "  President Approved At: " . $dbRecord->president_approved_at . "\n";
    
    // Wait a moment and check again
    echo "\n--- AFTER DELAY ---\n";
    sleep(2);
    $dbRecord2 = DB::table('travel_orders')->where('id', $travelOrder->id)->first();
    echo "Database record after delay:\n";
    echo "  Status: " . $dbRecord2->status . "\n";
    echo "  President Approved: " . var_export($dbRecord2->president_approved, true) . "\n";
    echo "  President Approved At: " . $dbRecord2->president_approved_at . "\n";
    
    // Check the model
    echo "\n--- MODEL CHECK ---\n";
    $travelOrder->refresh();
    echo "Model:\n";
    echo "  Status: " . $travelOrder->status . "\n";
    echo "  President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
    echo "  President Approved At: " . ($travelOrder->president_approved_at ? $travelOrder->president_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
    echo "  Remarks: " . $travelOrder->remarks . "\n";
    
    // Clean up
    $travelOrder->delete();
    
    echo "\nDebug completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}