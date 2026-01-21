<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate the division head authorization checks
// First, let's see if there's anyone who might be a division head based on old system
$employeesWithOldDivisionHeadRole = \App\Models\Employee::whereHas('officer', function($query) {
    $query->where('division_head', true);
})->with('officer', 'positions')->get();

echo "Employees with old division head role (from officers table):\n";
foreach($employeesWithOldDivisionHeadRole as $emp) {
    echo "- {$emp->first_name} {$emp->last_name}\n";
    echo "  Old system is_divisionhead: " . ($emp->is_divisionhead ? 'Yes' : 'No') . "\n";
    
    // Check their positions
    foreach($emp->positions as $pos) {
        echo "  Position: {$pos->position_name} (Primary: " . ($pos->is_primary ? 'Yes' : 'No') . 
             ", Division Head: " . ($pos->is_division_head ? 'Yes' : 'No') . ")\n";
    }
    echo "\n";
}

// Check if the current user (if authenticated) is a division head
if (auth()->check()) {
    $currentUser = auth()->user();
    $currentEmployee = $currentUser->employee;
    
    echo "Current logged-in user: {$currentUser->name}\n";
    if ($currentEmployee) {
        echo "Employee: {$currentEmployee->first_name} {$currentEmployee->last_name}\n";
        echo "Is division head (attribute): " . ($currentEmployee->is_divisionhead ? 'Yes' : 'No') . "\n";
        
        foreach($currentEmployee->positions as $pos) {
            echo "Position: {$pos->position_name} (Primary: " . ($pos->is_primary ? 'Yes' : 'No') . 
                 ", Division Head: " . ($pos->is_division_head ? 'Yes' : 'No') . ")\n";
        }
    } else {
        echo "No employee record for current user\n";
    }
} else {
    echo "No user is currently logged in\n";
}

// Get a travel order that needs division head approval
$travelOrder = \App\Models\TravelOrder::whereNull('divisionhead_approved')
    ->where('head_approved', true)
    ->with('employee', 'position', 'employee.positions')
    ->first();

if ($travelOrder && $travelOrder->employee) {
    echo "\nSample travel order needing division head approval:\n";
    echo "Destination: {$travelOrder->destination}\n";
    echo "Employee: {$travelOrder->employee->first_name} {$travelOrder->employee->last_name}\n";
    
    if ($travelOrder->position) {
        echo "Position: {$travelOrder->position->position_name}\n";
        echo "Division ID: {$travelOrder->position->division_id}\n";
        echo "Is Unit Head: " . ($travelOrder->position->is_unit_head ? 'Yes' : 'No') . "\n";
        echo "Is Division Head: " . ($travelOrder->position->is_division_head ? 'Yes' : 'No') . "\n";
        echo "Is VP: " . ($travelOrder->position->is_vp ? 'Yes' : 'No') . "\n";
        echo "Is President: " . ($travelOrder->position->is_president ? 'Yes' : 'No') . "\n";
    }
}