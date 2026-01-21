<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Final Debug: Division Head Approval Issue ===\n\n";

// Get the specific travel order that needs approval
$order124 = \App\Models\TravelOrder::find(124);
if (!$order124) {
    echo "Travel order ID 124 not found\n";
    exit;
}

echo "Travel Order ID 124 Details:\n";
echo "  Destination: {$order124->destination}\n";
echo "  Head Approved: " . ($order124->head_approved ? 'Yes' : 'No') . "\n";
echo "  Division Head Approved: " . ($order124->divisionhead_approved === null ? 'Pending' : ($order124->divisionhead_approved ? 'Yes' : 'No')) . "\n";

if ($order124->position) {
    echo "  Position: {$order124->position->position_name}\n";
    echo "  Division ID: {$order124->position->division_id}\n";
    if ($order124->position->division) {
        echo "  Division Name: {$order124->position->division->division_name}\n";
    }
    
    // Check if this position is eligible for division head approval
    $isEligible = !($order124->position->is_division_head || $order124->position->is_vp || $order124->position->is_president);
    echo "  Position Eligible for DH Approval: " . ($isEligible ? 'Yes' : 'No') . "\n";
    if (!$isEligible) {
        echo "    - Is Division Head: " . ($order124->position->is_division_head ? 'Yes' : 'No') . "\n";
        echo "    - Is VP: " . ($order124->position->is_vp ? 'Yes' : 'No') . "\n";
        echo "    - Is President: " . ($order124->position->is_president ? 'Yes' : 'No') . "\n";
    }
} else {
    echo "  NO POSITION ASSIGNED\n";
}

echo "\n";

// Find all division heads
echo "All Division Heads in System:\n";
$divisionHeads = \App\Models\Employee::whereHas('positions', function($query) {
    $query->where('is_division_head', true)->where('is_primary', true);
})->with('positions')->get();

if ($divisionHeads->isEmpty()) {
    echo "  NO DIVISION HEADS FOUND!\n";
} else {
    foreach($divisionHeads as $dh) {
        $primaryPos = $dh->positions->firstWhere('is_primary', true);
        if ($primaryPos) {
            echo "  - {$dh->first_name} {$dh->last_name}\n";
            echo "    Position: {$primaryPos->position_name}\n";
            echo "    Division ID: {$primaryPos->division_id}\n";
            echo "    is_divisionhead attribute: " . ($dh->is_divisionhead ? 'Yes' : 'No') . "\n";
            
            // Check if this division head can approve the travel order
            if ($order124->position) {
                $canApprove = $primaryPos->division_id === $order124->position->division_id;
                echo "    Can Approve Order 124: " . ($canApprove ? 'Yes' : 'No') . "\n";
            }
            echo "\n";
        }
    }
}

// Check if there's a division head for Division ID 18 specifically
echo "Division Head for Division ID 18 (College of Engineering):\n";
$division18Head = \App\Models\Employee::whereHas('positions', function($query) {
    $query->where('is_division_head', true)
          ->where('is_primary', true)
          ->where('division_id', 18);
})->with('positions')->first();

if ($division18Head) {
    echo "  Found: {$division18Head->first_name} {$division18Head->last_name}\n";
    echo "  is_divisionhead attribute: " . ($division18Head->is_divisionhead ? 'Yes' : 'No') . "\n";
} else {
    echo "  NOT FOUND - This is the problem!\n";
}

echo "\n=== Summary ===\n";
echo "Issue Analysis:\n";
echo "1. Travel Order 124 needs division head approval for Division ID 18\n";
echo "2. " . ($division18Head ? "There IS a division head for Division ID 18" : "THERE IS NO division head for Division ID 18") . "\n";
echo "3. If no division head exists for Division ID 18, approval will fail with 403\n";
echo "4. The user trying to approve might not be the correct division head\n\n";

if ($division18Head) {
    echo "Solution: Log in as {$division18Head->first_name} {$division18Head->last_name} to approve this travel order.\n";
} else {
    echo "Solution: Assign a division head role to someone in Division ID 18 (College of Engineering).\n";
}
?>