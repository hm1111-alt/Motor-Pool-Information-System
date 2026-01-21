<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Final Diagnostic for Division Head Approval ===\n\n";

// Get the travel order
$order124 = \App\Models\TravelOrder::find(124);
if (!$order124) {
    echo "Travel order ID 124 not found\n";
    exit;
}

echo "Travel Order ID 124:\n";
echo "  Position: {$order124->position->position_name}\n";
echo "  Division: {$order124->position->division->division_name} (ID: {$order124->position->division_id})\n";
echo "  Head Approved: " . ($order124->head_approved ? 'Yes' : 'No') . "\n";
echo "  Division Head Approved: " . ($order124->divisionhead_approved === null ? 'Pending' : ($order124->divisionhead_approved ? 'Yes' : 'No')) . "\n";

// Get the division head
$divisionHead = \App\Models\Employee::whereHas('positions', function($query) {
    $query->where('is_division_head', true)
          ->where('is_primary', true)
          ->where('division_id', 18);
})->with('positions')->first();

echo "\nDivision Head for College of Engineering:\n";
echo "  Name: {$divisionHead->first_name} {$divisionHead->last_name}\n";
echo "  User Email: {$divisionHead->user->email}\n";
echo "  Position: {$divisionHead->positions->firstWhere('is_primary', true)->position_name}\n";
echo "  Is Division Head: " . ($divisionHead->is_divisionhead ? 'Yes' : 'No') . "\n";

// Simulate the exact authorization checks from the DivisionHeadTravelOrderController
echo "\n=== Simulating DivisionHeadTravelOrderController Logic ===\n";

// Step 1: Check if the division head is actually a division head
$isDivisionHead = $divisionHead->is_divisionhead;
echo "1. Employee is division head: " . ($isDivisionHead ? 'PASS' : 'FAIL') . "\n";

// Step 2: Check division head's primary position
$divisionHeadPrimaryPosition = $divisionHead->positions()->where('is_primary', true)->first();
$divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
echo "2. Division head's division ID: {$divisionHeadDivisionId}\n";

// Step 3: Check travel order position
$travelOrderPosition = $order124->position;
echo "3. Travel order has position: " . ($travelOrderPosition ? 'PASS' : 'FAIL') . "\n";

if ($travelOrderPosition) {
    $travelOrderDivisionId = $travelOrderPosition->division_id;
    echo "4. Travel order division ID: {$travelOrderDivisionId}\n";
    
    // Step 4: Check if divisions match
    $sameDivision = $travelOrderDivisionId === $divisionHeadDivisionId;
    echo "5. Same division check: " . ($sameDivision ? 'PASS' : 'FAIL') . "\n";
    
    // Step 5: Check if travel order is from eligible position (not division head/VP/president)
    $isEligible = !($travelOrderPosition->is_division_head || $travelOrderPosition->is_vp || $travelOrderPosition->is_president);
    echo "6. Position eligible for DH approval: " . ($isEligible ? 'PASS' : 'FAIL') . "\n";
    
    // Step 6: Check if head approved
    $headApproved = $order124->head_approved;
    echo "7. Head approved: " . ($headApproved ? 'PASS' : 'FAIL') . "\n";
    
    // Step 7: Check if not already approved by division head
    $notAlreadyDHApproved = is_null($order124->divisionhead_approved);
    echo "8. Not already approved by division head: " . ($notAlreadyDHApproved ? 'PASS' : 'FAIL') . "\n";
    
    // Step 8: Check if not approved by higher authorities
    $notApprovedByHigher = is_null($order124->vp_approved) && is_null($order124->president_approved);
    echo "9. Not approved by higher authorities: " . ($notApprovedByHigher ? 'PASS' : 'FAIL') . "\n";
    
    // Final check: All conditions met?
    $allConditionsMet = $isDivisionHead && $sameDivision && $isEligible && $headApproved && $notAlreadyDHApproved && $notApprovedByHigher;
    echo "\nFINAL RESULT: " . ($allConditionsMet ? '✅ ALL CONDITIONS MET - APPROVAL SHOULD WORK' : '❌ SOME CONDITION FAILED') . "\n";
    
    if ($allConditionsMet) {
        echo "\nTo approve this travel order:\n";
        echo "1. Log in to the system as: {$divisionHead->user->email}\n";
        echo "2. Go to: http://127.0.0.1:8000/travel-orders/approvals/divisionhead\n";
        echo "3. Find travel order #124 in the list\n";
        echo "4. Click the 'Approve' button\n";
    }
}

echo "\n=== Login Information ===\n";
echo "Division Head Login:\n";
echo "  Email: {$divisionHead->user->email}\n";
echo "  Password: Use the default password or reset if needed\n";

?>