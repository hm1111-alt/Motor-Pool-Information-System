<?php
// Test script to verify the division head approval functionality is working properly

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Division Head Approval Functionality ===\n";

try {
    // Test getting a division head employee
    $divisionHead = \App\Models\Employee::whereHas('positions', function($query) {
        $query->where('is_division_head', true);
    })->with('positions')->first();
    
    if ($divisionHead) {
        echo "✓ Division Head found: {$divisionHead->first_name} {$divisionHead->last_name}\n";
        
        $primaryPosition = $divisionHead->positions()->where('is_primary', true)->first();
        if ($primaryPosition) {
            echo "  - Primary Position: {$primaryPosition->position_name}\n";
            echo "  - Division ID: {$primaryPosition->division_id}\n";
            
            // Test finding travel orders that should be approvable by this division head
            $travelOrders = \App\Models\TravelOrder::whereHas('position', function($query) use ($primaryPosition) {
                $query->where('division_id', $primaryPosition->division_id);
            })
            ->where('employee_id', '!=', $divisionHead->id) // Not their own
            ->whereNull('divisionhead_approved') // Pending division head approval
            ->where('head_approved', true) // Head has approved
            ->with('employee', 'position')
            ->get();
            
            echo "  - Travel orders pending division head approval: " . $travelOrders->count() . "\n";
            
            if ($travelOrders->count() > 0) {
                foreach ($travelOrders as $order) {
                    echo "    Order: {$order->destination} - Employee: {$order->employee->first_name} {$order->employee->last_name}\n";
                    
                    // Check if the position used for the travel order is eligible for division head approval
                    $orderPosition = $order->position;
                    if ($orderPosition) {
                        $isEligible = !($orderPosition->is_division_head || $orderPosition->is_vp || $orderPosition->is_president);
                        echo "      - Position: {$orderPosition->position_name} - Eligible: " . ($isEligible ? 'Yes' : 'No') . "\n";
                        
                        if ($orderPosition->is_division_head) echo "        - Is Division Head: Yes\n";
                        if ($orderPosition->is_vp) echo "        - Is VP: Yes\n";
                        if ($orderPosition->is_president) echo "        - Is President: Yes\n";
                    } else {
                        echo "      - No position assigned\n";
                    }
                }
            }
        }
    } else {
        echo "✗ No division head found in database\n";
        
        // Check for any employee that might be a division head
        $employees = \App\Models\Employee::with('positions')->get();
        foreach ($employees as $emp) {
            $isDivisionHead = $emp->positions->contains('is_division_head', true);
            if ($isDivisionHead) {
                echo "  Found employee {$emp->first_name} {$emp->last_name} with division head position\n";
                foreach ($emp->positions as $pos) {
                    if ($pos->is_division_head) {
                        echo "    - Position: {$pos->position_name} (Division Head)\n";
                    }
                }
            }
        }
    }
    
    // Check for any travel orders that might be pending division head approval
    $pendingOrders = \App\Models\TravelOrder::whereNull('divisionhead_approved')
        ->where('head_approved', true)
        ->with('employee', 'position')
        ->get();
    
    echo "\n  - Total travel orders pending division head approval: " . $pendingOrders->count() . "\n";
    
    foreach ($pendingOrders->take(5) as $order) { // Just check first 5
        if ($order->employee) {
            echo "    Order: {$order->destination} - Employee: {$order->employee->first_name} {$order->employee->last_name}\n";
            
            $orderPosition = $order->position;
            if ($orderPosition) {
                echo "      - Position: {$orderPosition->position_name}\n";
                echo "      - Division ID: {$orderPosition->division_id}\n";
                echo "      - Is Unit Head: " . ($orderPosition->is_unit_head ? 'Yes' : 'No') . "\n";
                echo "      - Is Division Head: " . ($orderPosition->is_division_head ? 'Yes' : 'No') . "\n";
                echo "      - Is VP: " . ($orderPosition->is_vp ? 'Yes' : 'No') . "\n";
                echo "      - Is President: " . ($orderPosition->is_president ? 'Yes' : 'No') . "\n";
            }
        } else {
            echo "    Order: {$order->destination} - Employee record not found\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Error trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";