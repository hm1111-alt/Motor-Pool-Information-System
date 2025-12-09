<?php
require_once 'vendor/autoload.php';

use App\Models\TravelOrder;

// Test 1: Check model defaults
$order = new TravelOrder();
echo "Model defaults:\n";
echo "head_approved: " . var_export($order->head_approved, true) . "\n";
echo "vp_approved: " . var_export($order->vp_approved, true) . "\n\n";

// Test 2: Check database creation without explicit values
$order = TravelOrder::create([
    'employee_id' => 1,
    'destination' => 'Test',
    'date_from' => '2025-12-10',
    'date_to' => '2025-12-12',
    'purpose' => 'Test purpose',
    'status' => 'pending'
]);

echo "After creation:\n";
echo "head_approved: " . var_export($order->head_approved, true) . "\n";
echo "vp_approved: " . var_export($order->vp_approved, true) . "\n";
echo "ID: " . $order->id . "\n";

// Clean up
$order->delete();