<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

echo "=== Debug Division Head Approval Authorization ===\n\n";

try {
    // Find a division head employee
    $divisionHead = Employee::whereHas('officer', function($query) {
        $query->where('division_head', true);
    })->first();
    
    if (!$divisionHead) {
        echo "No division head found\n";
        exit;
    }
    
    echo "Division head: {$divisionHead->first_name} {$divisionHead->last_name}\n";
    echo "Division ID: {$divisionHead->division_id}\n";
    echo "Is division head: " . ($divisionHead->is_divisionhead ? 'Yes' : 'No') . "\n\n";
    
    // Find a regular employee in the same division
    $regularEmployee = Employee::where('division_id', $divisionHead->division_id)
                              ->whereHas('officer', function($query) {
                                  $query->where('unit_head', false)
                                        ->where('division_head', false)
                                        ->where('vp', false)
                                        ->where('president', false);
                              })
                              ->first();
    
    if (!$regularEmployee) {
        echo "No regular employee found in the same division with all false officer flags\n";
        // Try finding a regular employee with no officer record
        $regularEmployee = Employee::where('division_id', $divisionHead->division_id)
                                  ->whereDoesntHave('officer')
                                  ->first();
        if ($regularEmployee) {
            echo "Found regular employee without officer record: {$regularEmployee->first_name} {$regularEmployee->last_name}\n";
        }
    } else {
        echo "Found regular employee with all false officer flags: {$regularEmployee->first_name} {$regularEmployee->last_name}\n";
    }
    
    if ($regularEmployee) {
        echo "Regular employee details:\n";
        echo "  Division ID: {$regularEmployee->division_id}\n";
        echo "  Is head: " . ($regularEmployee->is_head ? 'Yes' : 'No') . "\n";
        echo "  Is division head: " . ($regularEmployee->is_divisionhead ? 'Yes' : 'No') . "\n";
        echo "  Is VP: " . ($regularEmployee->is_vp ? 'Yes' : 'No') . "\n";
        echo "  Is president: " . ($regularEmployee->is_president ? 'Yes' : 'No') . "\n";
        if ($regularEmployee->officer) {
            echo "  Officer record: ";
            echo "unit_head={$regularEmployee->officer->unit_head}, ";
            echo "division_head={$regularEmployee->officer->division_head}, ";
            echo "vp={$regularEmployee->officer->vp}, ";
            echo "president={$regularEmployee->officer->president}\n";
        } else {
            echo "  No officer record\n";
        }
        
        // Create a travel order and approve by head
        $travelOrder = TravelOrder::create([
            'employee_id' => $regularEmployee->id,
            'destination' => 'Debug Approval Test',
            'date_from' => '2025-12-01',
            'date_to' => '2025-12-03',
            'purpose' => 'Debug approval test',
            'status' => 'pending'
        ]);
        
        $travelOrder->update([
            'head_approved' => true,
            'head_approved_at' => now(),
            'status' => 'pending'
        ]);
        
        $travelOrder->refresh();
        echo "\nTravel order after head approval:\n";
        echo "  ID: {$travelOrder->id}\n";
        echo "  Employee division: {$travelOrder->employee->division_id}\n";
        echo "  Division head division: {$divisionHead->division_id}\n";
        echo "  Same division: " . (($travelOrder->employee->division_id === $divisionHead->division_id) ? 'Yes' : 'No') . "\n";
        echo "  Head approved: " . ($travelOrder->head_approved ? 'Yes' : 'No') . "\n";
        echo "  Division head approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
        echo "  Division head approved is null: " . (is_null($travelOrder->divisionhead_approved) ? 'Yes' : 'No') . "\n";
        echo "  Employee is division head: " . ($travelOrder->employee->is_divisionhead ? 'Yes' : 'No') . "\n";
        echo "  Employee is VP: " . ($travelOrder->employee->is_vp ? 'Yes' : 'No') . "\n";
        echo "  Employee is president: " . ($travelOrder->employee->is_president ? 'Yes' : 'No') . "\n";
        
        // Test each authorization condition
        echo "\n--- Testing authorization conditions ---\n";
        $condition1 = ($travelOrder->employee->division_id === $divisionHead->division_id);
        echo "Condition 1 (same division): " . ($condition1 ? 'PASS' : 'FAIL') . "\n";
        
        $condition2 = !($travelOrder->employee->is_divisionhead || $travelOrder->employee->is_vp || $travelOrder->employee->is_president);
        echo "Condition 2 (not division head/VP/president): " . ($condition2 ? 'PASS' : 'FAIL') . "\n";
        
        $condition3 = $travelOrder->head_approved;
        echo "Condition 3 (head approved): " . ($condition3 ? 'PASS' : 'FAIL') . "\n";
        
        $condition4 = is_null($travelOrder->divisionhead_approved);
        echo "Condition 4 (not division head approved): " . ($condition4 ? 'PASS' : 'FAIL') . "\n";
        
        $allConditionsPass = $condition1 && $condition2 && $condition3 && $condition4;
        echo "\nAll conditions pass: " . ($allConditionsPass ? 'YES - Should be allowed to approve' : 'NO - Will get 403 Forbidden') . "\n";
        
        // Clean up
        $travelOrder->delete();
    } else {
        echo "Could not find a suitable regular employee in the same division\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}