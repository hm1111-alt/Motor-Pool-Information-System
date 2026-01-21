<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check the employees who have old division head role
$employeesWithOldDivisionHeadRole = \App\Models\Employee::whereHas('officer', function($query) {
    $query->where('division_head', true);
})->with('officer', 'positions')->get();

echo "Checking division head employees and their divisions:\n\n";

foreach($employeesWithOldDivisionHeadRole as $emp) {
    echo "{$emp->first_name} {$emp->last_name}\n";
    echo "  Old system is_divisionhead: " . ($emp->is_divisionhead ? 'Yes' : 'No') . "\n";
    
    $primaryPosition = $emp->positions()->where('is_primary', true)->first();
    if ($primaryPosition) {
        echo "  Primary Position: {$primaryPosition->position_name}\n";
        echo "  Primary Position Division ID: {$primaryPosition->division_id}\n";
        echo "  Primary Position Division Name: ";
        if ($primaryPosition->division) {
            echo $primaryPosition->division->division_name;
        } else {
            echo "NOT FOUND (ID: {$primaryPosition->division_id})";
        }
        echo "\n";
    }
    
    // Check all their positions
    foreach($emp->positions as $pos) {
        echo "  Position: {$pos->position_name} (Primary: " . ($pos->is_primary ? 'Yes' : 'No') . 
             ", Division ID: {$pos->division_id}";
        if ($pos->division) {
            echo " -> {$pos->division->division_name}";
        }
        echo ")\n";
    }
    echo "\n";
}

// Check travel orders that need division head approval
$travelOrder = \App\Models\TravelOrder::whereNull('divisionhead_approved')
    ->where('head_approved', true)
    ->with('employee', 'position', 'position.division')
    ->first();

if ($travelOrder && $travelOrder->employee) {
    echo "Sample travel order needing division head approval:\n";
    echo "  Destination: {$travelOrder->destination}\n";
    echo "  Employee: {$travelOrder->employee->first_name} {$travelOrder->employee->last_name}\n";
    
    if ($travelOrder->position) {
        echo "  Position: {$travelOrder->position->position_name}\n";
        echo "  Position Division ID: {$travelOrder->position->division_id}\n";
        echo "  Position Division Name: ";
        if ($travelOrder->position->division) {
            echo $travelOrder->position->division->division_name;
        } else {
            echo "NOT FOUND (ID: {$travelOrder->position->division_id})";
        }
        echo "\n";
    }
}