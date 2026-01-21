<?php
require_once 'vendor/autoload.php';

// Start session to check current user
session_start();

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Since we can't simulate a real session here, let's check the current user's permissions directly
// We'll simulate what happens when the division head tries to approve

use Illuminate\Support\Facades\Auth;

echo "=== Checking Current Session & Permissions ===\n\n";

// Let's manually simulate what happens when division head tries to approve travel order 124
$order124 = \App\Models\TravelOrder::find(124);
if (!$order124) {
    echo "Travel order ID 124 not found\n";
    exit;
}

echo "Travel Order 124 Details:\n";
echo "  Position: {$order124->position->position_name}\n";
echo "  Division: {$order124->position->division->division_name} (ID: {$order124->position->division_id})\n";
echo "  Head Approved: " . ($order124->head_approved ? 'Yes' : 'No') . "\n";
echo "  Division Head Approved: " . ($order124->divisionhead_approved === null ? 'Pending' : ($order124->divisionhead_approved ? 'Yes' : 'No')) . "\n";

// Now let's get the actual division head for Division 18 to test the authorization logic
$divisionHead = \App\Models\Employee::whereHas('positions', function($query) {
    $query->where('is_division_head', true)
          ->where('is_primary', true)
          ->where('division_id', 18);
})->with('positions')->first();

if (!$divisionHead) {
    echo "ERROR: No division head found for Division 18\n";
    exit;
}

echo "\nSimulated Division Head (would be current user if logged in as division head):\n";
echo "  Name: {$divisionHead->first_name} {$divisionHead->last_name}\n";
echo "  Is Division Head: " . ($divisionHead->is_divisionhead ? 'Yes' : 'No') . "\n";

// Test the exact logic from DivisionHeadTravelOrderController
echo "\n=== Testing DivisionHeadTravelOrderController Authorization Logic ===\n";

// 1. Ensure the user is a division head
$isDivisionHead = $divisionHead->is_divisionhead;
echo "1. User is division head: " . ($isDivisionHead ? 'PASS' : 'FAIL') . "\n";

// 2. Get division head's primary position
$divisionHeadPrimaryPosition = $divisionHead->positions()->where('is_primary', true)->first();
$divisionHeadDivisionId = $divisionHeadPrimaryPosition ? $divisionHeadPrimaryPosition->division_id : null;
echo "2. Division head's division ID: " . ($divisionHeadDivisionId ?: 'NULL') . "\n";

// 3. Get travel order position
$travelOrderPosition = $order124->position;
echo "3. Travel order has position: " . ($travelOrderPosition ? 'PASS' : 'FAIL') . "\n";

if ($travelOrderPosition) {
    $travelOrderDivisionId = $travelOrderPosition->division_id;
    echo "4. Travel order division ID: {$travelOrderDivisionId}\n";
    
    // 4. Ensure the division head can only approve travel orders from their division
    $sameDivision = $travelOrderDivisionId === $divisionHeadDivisionId;
    echo "5. Same division check: " . ($sameDivision ? 'PASS' : 'FAIL') . "\n";
    
    // 5. Ensure the travel order is from a regular employee or unit head (not division head/VP/president)
    $isEligible = !($travelOrderPosition->is_division_head || $travelOrderPosition->is_vp || $travelOrderPosition->is_president);
    echo "6. Position eligible for DH approval: " . ($isEligible ? 'PASS' : 'FAIL') . "\n";
    
    // 6. Ensure the head has already approved
    $headApproved = $order124->head_approved;
    echo "7. Head approved: " . ($headApproved ? 'PASS' : 'FAIL') . "\n";
    
    // 7. Ensure the travel order hasn't already been approved by division head
    $notAlreadyDHApproved = is_null($order124->divisionhead_approved);
    echo "8. Not already approved by division head: " . ($notAlreadyDHApproved ? 'PASS' : 'FAIL') . "\n";
    
    // 8. Ensure not approved by higher authorities
    $notApprovedByHigher = is_null($order124->vp_approved) && is_null($order124->president_approved);
    echo "9. Not approved by higher authorities: " . ($notApprovedByHigher ? 'PASS' : 'FAIL') . "\n";
    
    $allChecks = $isDivisionHead && $sameDivision && $isEligible && $headApproved && $notAlreadyDHApproved && $notApprovedByHigher;
    echo "\nALL CHECKS PASSED: " . ($allChecks ? 'YES' : 'NO') . "\n";
    
    if ($allChecks) {
        echo "\n✅ AUTHORIZATION SHOULD WORK - All conditions are met\n";
        echo "If you're still getting 403, make sure you are logged in as:\n";
        echo "- Email: {$divisionHead->user->email}\n";
        echo "- Or verify that you're accessing the correct route\n";
    } else {
        echo "\n❌ AUTHORIZATION WILL FAIL - Some conditions are not met\n";
    }
} else {
    echo "❌ Travel order has no position assigned\n";
}

?>