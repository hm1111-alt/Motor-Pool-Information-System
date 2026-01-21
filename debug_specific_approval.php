<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the specific travel order that's causing issues
$travelOrder = \App\Models\TravelOrder::where('destination', 'Quezon City')->first();

if (!$travelOrder) {
    echo "No travel order found with destination 'Quezon City'\n";
    exit;
}

echo "Travel Order ID: {$travelOrder->id}\n";
echo "Destination: {$travelOrder->destination}\n";
echo "Head Approved: " . ($travelOrder->head_approved ? 'Yes' : 'No') . "\n";
echo "Division Head Approved: " . ($travelOrder->divisionhead_approved === null ? 'Pending' : ($travelOrder->divisionhead_approved ? 'Yes' : 'No')) . "\n";

if ($travelOrder->position) {
    echo "Position: {$travelOrder->position->position_name}\n";
    echo "Position Division ID: {$travelOrder->position->division_id}\n";
    if ($travelOrder->position->division) {
        echo "Division Name: {$travelOrder->position->division->division_name}\n";
    }
} else {
    echo "NO POSITION ASSIGNED\n";
}

// Check if there are division heads in the system
$divisionHeads = \App\Models\Employee::whereHas('positions', function($query) {
    $query->where('is_division_head', true)->where('is_primary', true);
})->with('positions')->get();

echo "\nDivision Heads in the system:\n";
foreach($divisionHeads as $dh) {
    $primaryPos = $dh->positions->firstWhere('is_primary', true);
    if ($primaryPos) {
        echo "- {$dh->first_name} {$dh->last_name}: {$primaryPos->position_name} (Division ID: {$primaryPos->division_id})\n";
    }
}

// Simulate the exact authorization checks that happen in the approve method
echo "\nSimulating approve method authorization checks:\n";

// Find a division head for the same division as the travel order
if ($travelOrder->position) {
    $divisionId = $travelOrder->position->division_id;
    
    $matchingDivisionHead = \App\Models\Employee::whereHas('positions', function($query) use ($divisionId) {
        $query->where('is_division_head', true)
              ->where('is_primary', true)
              ->where('division_id', $divisionId);
    })->first();
    
    if ($matchingDivisionHead) {
        echo "Found division head for Division ID {$divisionId}: {$matchingDivisionHead->first_name} {$matchingDivisionHead->last_name}\n";
        
        $dhPrimaryPos = $matchingDivisionHead->positions->firstWhere('is_primary', true);
        
        // Check 1: Is the employee a division head?
        echo "1. Employee is division head: " . ($matchingDivisionHead->is_divisionhead ? 'PASS' : 'FAIL') . "\n";
        
        // Check 2: Same division?
        $sameDivision = $dhPrimaryPos->division_id === $travelOrder->position->division_id;
        echo "2. Same division: " . ($sameDivision ? 'PASS' : 'FAIL') . "\n";
        
        // Check 3: Position eligibility (not division head/VP/president)?
        $eligible = !($travelOrder->position->is_division_head || $travelOrder->position->is_vp || $travelOrder->position->is_president);
        echo "3. Position eligible (not DH/VP/Pres): " . ($eligible ? 'PASS' : 'FAIL') . "\n";
        
        // Check 4: Head already approved?
        echo "4. Head approved: " . ($travelOrder->head_approved ? 'PASS' : 'FAIL') . "\n";
        
        // Check 5: Not already approved by division head?
        echo "5. Not already approved by DH: " . (is_null($travelOrder->divisionhead_approved) ? 'PASS' : 'FAIL') . "\n";
        
        $allPass = $matchingDivisionHead->is_divisionhead && $sameDivision && $eligible && $travelOrder->head_approved && is_null($travelOrder->divisionhead_approved);
        echo "\nAll checks pass: " . ($allPass ? 'YES - Should be able to approve' : 'NO - Will get 403') . "\n";
        
    } else {
        echo "No division head found for Division ID {$divisionId}\n";
    }
} else {
    echo "Cannot check authorization - travel order has no position assigned\n";
}