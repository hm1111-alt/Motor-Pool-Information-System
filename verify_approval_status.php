<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Verifying Travel Order Approval Status ===\n\n";

// Check travel order 124's current status
$order124 = \App\Models\TravelOrder::find(124);
if (!$order124) {
    echo "Travel order ID 124 not found\n";
    exit;
}

echo "Travel Order ID 124 Current Status:\n";
echo "  Head Approved: " . ($order124->head_approved ? 'Yes' : 'No') . "\n";
echo "  Division Head Approved: " . ($order124->divisionhead_approved === null ? 'Pending' : ($order124->divisionhead_approved ? 'Yes' : 'No')) . "\n";
echo "  VP Approved: " . ($order124->vp_approved === null ? 'Pending' : ($order124->vp_approved ? 'Yes' : 'No')) . "\n";
echo "  President Approved: " . ($order124->president_approved === null ? 'Pending' : ($order124->president_approved ? 'Yes' : 'No')) . "\n";
echo "  Overall Status: {$order124->status}\n";

// Check if it should appear in approved tab
$shouldAppearInApproved = !is_null($order124->divisionhead_approved) && $order124->divisionhead_approved === true;
echo "\nShould appear in Approved tab: " . ($shouldAppearInApproved ? 'Yes' : 'No') . "\n";

// Check what tab filtering logic would show
echo "\nTab Filtering Logic Check:\n";
echo "  For 'pending' tab: divisionhead_approved IS NULL = " . (is_null($order124->divisionhead_approved) ? 'true' : 'false') . "\n";
echo "  For 'approved' tab: divisionhead_approved = 1 = " . ($order124->divisionhead_approved === true ? 'true' : 'false') . "\n";
echo "  For 'cancelled' tab: divisionhead_approved = 0 = " . ($order124->divisionhead_approved === false ? 'true' : 'false') . "\n";

// Show some other travel orders for comparison
echo "\n=== Other Travel Orders for Reference ===\n";
$sampleOrders = \App\Models\TravelOrder::whereIn('id', [124, 125, 126])->get();
foreach($sampleOrders as $order) {
    echo "Order ID {$order->id}:\n";
    echo "  Division Head Approved: " . ($order->divisionhead_approved === null ? 'Pending' : ($order->divisionhead_approved ? 'Yes' : 'No')) . "\n";
    echo "  Status: {$order->status}\n\n";
}

?>