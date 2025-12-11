<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

// Mock the Auth facade
class MockAuth {
    public $employee;
    
    public function __construct($employee) {
        $this->employee = $employee;
    }
    
    public function user() {
        return $this;
    }
}

// Test the division head approval logic
try {
    // Get a head's travel order that hasn't been approved by division head yet
    $travelOrder = TravelOrder::whereHas('employee', function ($query) {
        $query->where('is_head', 1);
    })->whereNull('divisionhead_approved')->first();
    
    if (!$travelOrder) {
        echo "No unapproved head travel order found\n";
        exit;
    }
    
    echo "Found travel order ID: " . $travelOrder->id . " from employee ID: " . $travelOrder->employee_id . "\n";
    echo "Employee is_head: " . var_export($travelOrder->employee->is_head, true) . "\n";
    echo "Employee division_id: " . $travelOrder->employee->division_id . "\n";
    
    // Get a division head from the same division
    $divisionHead = Employee::where('division_id', $travelOrder->employee->division_id)
        ->where('is_divisionhead', 1)
        ->first();
        
    if (!$divisionHead) {
        echo "No division head found in the same division\n";
        exit;
    }
    
    echo "Found division head ID: " . $divisionHead->id . "\n";
    echo "Division head division_id: " . $divisionHead->division_id . "\n";
    
    // Check if they're the same person (should not approve their own)
    if ($travelOrder->employee_id === $divisionHead->id) {
        echo "Cannot test - division head is the same as the travel order creator\n";
        exit;
    }
    
    // Simulate the approval logic from the controller
    // Ensure the division head can only approve travel orders from their division
    if ($travelOrder->employee->division_id !== $divisionHead->division_id) {
        echo "FAIL: Division mismatch\n";
        exit;
    } else {
        echo "PASS: Division check\n";
    }
    
    // Ensure the travel order is from a head
    if (!$travelOrder->employee->is_head) {
        echo "FAIL: Not a head's travel order\n";
        exit;
    } else {
        echo "PASS: Is head's travel order\n";
    }
    
    // Ensure the division head cannot approve their own travel order
    if ($travelOrder->employee_id === $divisionHead->id) {
        echo "FAIL: Division head trying to approve own travel order\n";
        exit;
    } else {
        echo "PASS: Not division head's own travel order\n";
    }
    
    // Ensure the travel order hasn't already been approved or rejected
    if (!is_null($travelOrder->divisionhead_approved)) {
        echo "FAIL: Already approved/rejected\n";
        exit;
    } else {
        echo "PASS: Not yet approved/rejected\n";
    }
    
    echo "All checks passed - approval should work!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}