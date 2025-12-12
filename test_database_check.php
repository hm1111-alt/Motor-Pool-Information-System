<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

// Test to check what's actually being saved in the database
try {
    echo "=== Testing Database Record Creation ===\n\n";
    
    // Get a president employee
    $president = Employee::where('is_president', 1)->first();
    
    if (!$president) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $president->first_name . " " . $president->last_name . " (ID: " . $president->id . ")\n";
    
    // Create a travel order for this president
    $travelOrder = TravelOrder::create([
        'employee_id' => $president->id,
        'destination' => 'Database Test Destination',
        'date_from' => '2025-12-15',
        'date_to' => '2025-12-16',
        'purpose' => 'Database test purpose',
        'status' => 'approved',
        'president_approved' => true,
        'president_approved_at' => now(),
    ]);
    
    echo "Created travel order ID: " . $travelOrder->id . "\n";
    
    // Check what's actually in the database
    $dbRecord = DB::table('travel_orders')->where('id', $travelOrder->id)->first();
    
    echo "Database record:\n";
    echo "  status: " . $dbRecord->status . "\n";
    echo "  president_approved: " . var_export($dbRecord->president_approved, true) . "\n";
    echo "  president_approved_at: " . $dbRecord->president_approved_at . "\n";
    
    // Check the model attributes
    echo "\nModel attributes:\n";
    echo "  status: " . $travelOrder->status . "\n";
    echo "  president_approved: " . var_export($travelOrder->president_approved, true) . "\n";
    echo "  president_approved_at: " . ($travelOrder->president_approved_at ? $travelOrder->president_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
    echo "  remarks: " . $travelOrder->remarks . "\n";
    
    // Wait a moment and check again
    sleep(1);
    
    // Reload from database
    $travelOrder->refresh();
    
    echo "\nAfter refresh:\n";
    echo "  status: " . $travelOrder->status . "\n";
    echo "  president_approved: " . var_export($travelOrder->president_approved, true) . "\n";
    echo "  president_approved_at: " . ($travelOrder->president_approved_at ? $travelOrder->president_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
    echo "  remarks: " . $travelOrder->remarks . "\n";
    
    // Clean up - delete the test travel order
    $travelOrder->delete();
    
    echo "\nTest completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}