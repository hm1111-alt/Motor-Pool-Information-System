<?php
// Test script to verify the approval functionality is working properly

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Approval Functionality ===\n";

try {
    // Test getting a unit head employee
    $unitHead = \App\Models\Employee::whereHas('positions', function($query) {
        $query->where('is_unit_head', true);
    })->with('positions')->first();
    
    if ($unitHead) {
        echo "✓ Unit Head found: {$unitHead->first_name} {$unitHead->last_name}\n";
        
        // Find travel orders that should be pending for approval by this unit head
        $primaryPosition = $unitHead->positions()->where('is_primary', true)->first();
        if ($primaryPosition) {
            echo "  - Primary Position: {$primaryPosition->position_name}\n";
            echo "  - Unit ID: {$primaryPosition->unit_id}\n";
            
            // Test finding travel orders from the same unit
            $travelOrders = \App\Models\TravelOrder::whereHas('position', function($query) use ($primaryPosition) {
                $query->where('unit_id', $primaryPosition->unit_id);
            })
            ->where('employee_id', '!=', $unitHead->id) // Not their own
            ->whereNull('head_approved') // Pending head approval
            ->with('employee', 'position')
            ->get();
            
            echo "  - Travel orders pending approval: " . $travelOrders->count() . "\n";
            
            if ($travelOrders->count() > 0) {
                $firstOrder = $travelOrders->first();
                echo "    First order: {$firstOrder->destination} - Employee: {$firstOrder->employee->first_name} {$firstOrder->employee->last_name}\n";
                
                // Test the approval show method by simulating what the controller would do
                $travelOrderPosition = $firstOrder->position;
                $travelOrderUnitId = $travelOrderPosition ? $travelOrderPosition->unit_id : null;
                $headUnitId = $primaryPosition->unit_id;
                
                $canView = $travelOrderUnitId === $headUnitId;
                echo "    Can view in approval interface: " . ($canView ? 'Yes' : 'No') . "\n";
            }
        }
    } else {
        echo "✗ No unit head found in database\n";
    }
    
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
        }
    } else {
        echo "✗ No division head found in database\n";
    }
    
    // Test getting a VP employee
    $vp = \App\Models\Employee::whereHas('positions', function($query) {
        $query->where('is_vp', true);
    })->with('positions')->first();
    
    if ($vp) {
        echo "✓ VP found: {$vp->first_name} {$vp->last_name}\n";
        
        $primaryPosition = $vp->positions()->where('is_primary', true)->first();
        if ($primaryPosition) {
            echo "  - Primary Position: {$primaryPosition->position_name}\n";
            echo "  - Office ID: {$primaryPosition->office_id}\n";
        }
    } else {
        echo "✗ No VP found in database\n";
    }
    
    // Test getting a President employee
    $president = \App\Models\Employee::whereHas('positions', function($query) {
        $query->where('is_president', true);
    })->with('positions')->first();
    
    if ($president) {
        echo "✓ President found: {$president->first_name} {$president->last_name}\n";
    } else {
        echo "✗ No President found in database\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Error trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";