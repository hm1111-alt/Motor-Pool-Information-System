<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate the current user and check permissions
use Illuminate\Support\Facades\Auth;

// Since we can't simulate an actual authenticated session here,
// let's manually check what happens when we try to access the travel order

echo "=== Detailed Debug for Travel Order 124 Approval ===\n\n";

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
} else {
    echo "  NO POSITION ASSIGNED\n";
}

echo "\n=== Checking Division Head Availability ===\n";

// Check if there's a division head for Division ID 18
$division18Head = \App\Models\Employee::whereHas('positions', function($query) {
    $query->where('is_division_head', true)
          ->where('is_primary', true)
          ->where('division_id', 18);
})->with('positions')->first();

if ($division18Head) {
    echo "✅ Found division head for Division ID 18:\n";
    echo "  Name: {$division18Head->first_name} {$division18Head->last_name}\n";
    echo "  is_divisionhead attribute: " . ($division18Head->is_divisionhead ? 'Yes' : 'No') . "\n";
    $primaryPos = $division18Head->positions->firstWhere('is_primary', true);
    if ($primaryPos) {
        echo "  Position: {$primaryPos->position_name}\n";
        echo "  Division ID: {$primaryPos->division_id}\n";
        echo "  Is Division Head (position): " . ($primaryPos->is_division_head ? 'Yes' : 'No') . "\n";
    }
} else {
    echo "❌ No division head found for Division ID 18\n";
}

echo "\n=== Simulating Division Head Controller Logic ===\n";

// Manually simulate the division head controller logic
if ($order124->position && $order124->position->division_id == 18) {
    // This is the scenario where someone tries to approve travel order 124
    
    // Check all the conditions in DivisionHeadTravelOrderController
    echo "Travel order 124 belongs to Division ID 18, so division head approval is needed.\n";
    echo "Let's simulate the approval conditions:\n\n";
    
    // Condition 1: Travel order has position
    echo "1. Travel order has position: " . ($order124->position ? 'PASS' : 'FAIL') . "\n";
    
    // Condition 2: Travel order is from regular employee/unit head (not division head/VP/president)
    $isEligible = !($order124->position->is_division_head || $order124->position->is_vp || $order124->position->is_president);
    echo "2. Position eligible for DH approval: " . ($isEligible ? 'PASS' : 'FAIL') . "\n";
    
    // Condition 3: Head has approved
    echo "3. Head approved: " . ($order124->head_approved ? 'PASS' : 'FAIL') . "\n";
    
    // Condition 4: Not already approved by division head
    echo "4. Not already approved by division head: " . (is_null($order124->divisionhead_approved) ? 'PASS' : 'FAIL') . "\n";
    
    // Condition 5: Not approved by higher authorities (VP/President)
    $notApprovedByHigher = is_null($order124->vp_approved) && is_null($order124->president_approved);
    echo "5. Not approved by higher authorities (VP/President): " . ($notApprovedByHigher ? 'PASS' : 'FAIL') . "\n";
    
    if ($division18Head) {
        echo "\nThe travel order meets all conditions for division head approval.\n";
        echo "However, the person trying to approve must be the actual division head of Division ID 18.\n";
        echo "Current division head: {$division18Head->first_name} {$division18Head->last_name}\n";
        echo "Are you logged in as this person?\n";
    } else {
        echo "\nNo division head is assigned to Division ID 18, so approval is impossible.\n";
    }
} else {
    echo "Travel order does not belong to Division ID 18\n";
}

echo "\n=== Troubleshooting Steps ===\n";
echo "1. Make sure you are logged in as the division head for College of Engineering\n";
echo "2. The division head should be: {$division18Head->first_name} {$division18Head->last_name}\n";
echo "3. Access the URL: http://127.0.0.1:8000/travel-orders/approvals/divisionhead\n";
echo "4. Find travel order #124 in the list and approve it there\n";

?>