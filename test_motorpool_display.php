<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Test that President travel orders appear in the Motorpool dashboard
try {
    echo "=== Testing President Travel Order in Motorpool Dashboard ===\n\n";
    
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
        'destination' => 'Presidential Destination',
        'date_from' => '2025-12-20',
        'date_to' => '2025-12-22',
        'purpose' => 'Official presidential business',
        'status' => 'approved', // Automatically approved
        'president_approved' => true, // Automatically set to true
        'president_approved_at' => now(), // Automatically set
    ]);
    
    echo "Created travel order ID: " . $travelOrder->id . "\n";
    echo "Status: " . $travelOrder->status . "\n";
    echo "President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
    echo "Remarks: " . $travelOrder->remarks . "\n";
    
    // Get all approved travel orders (similar to the MotorpoolAdminController logic)
    $approvedTravelOrders = TravelOrder::where(function ($query) {
        // Regular employees and heads approved by VP
        $query->whereHas('employee', function ($subQuery) {
            $subQuery->where('is_divisionhead', 0)
                      ->where('is_vp', 0)
                      ->where('is_president', 0)
                      ->orWhereNull('is_divisionhead')
                      ->orWhereNull('is_vp')
                      ->orWhereNull('is_president');
        })->where('vp_approved', true)
        // Division heads approved by President
        ->orWhereHas('employee', function ($subQuery) {
            $subQuery->where('is_divisionhead', 1)
                      ->where('is_vp', 0)
                      ->where('is_president', 0);
        })->where('president_approved', true)
        // VPs approved by President
        ->orWhereHas('employee', function ($subQuery) {
            $subQuery->where('is_vp', 1)
                      ->where('is_president', 0);
        })->where('president_approved', true)
        // Presidents self-created (automatically approved)
        ->orWhereHas('employee', function ($subQuery) {
            $subQuery->where('is_president', 1);
        })->where('president_approved', true);
    })
    ->orderBy('updated_at', 'desc')
    ->get();
    
    echo "\nTotal approved travel orders in system: " . $approvedTravelOrders->count() . "\n";
    
    // Check if our President travel order is in the list
    $found = false;
    foreach ($approvedTravelOrders as $order) {
        if ($order->id == $travelOrder->id) {
            echo "✓ President travel order ID " . $order->id . " found in Motorpool dashboard\n";
            echo "  Employee: " . $order->employee->first_name . " " . $order->employee->last_name . "\n";
            echo "  Status: " . $order->status . "\n";
            echo "  Remarks: " . $order->remarks . "\n";
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        echo "✗ President travel order not found in Motorpool dashboard\n";
    }
    
    // Clean up - delete the test travel order
    $travelOrder->delete();
    
    echo "\nTest completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}