<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Employee;

// Simulate a head user
$headEmployee = Employee::where('unit_id', 104)->where('is_head', true)->first();

if (!$headEmployee) {
    echo "No head found in unit 104\n";
    exit;
}

echo "Head Employee: " . $headEmployee->first_name . " " . $headEmployee->last_name . " (ID: " . $headEmployee->id . ")\n";
echo "Unit ID: " . $headEmployee->unit_id . "\n";

// Simulate the query from HeadTravelOrderController
$travelOrders = \App\Models\TravelOrder::whereHas('employee', function ($query) use ($headEmployee) {
        $query->where('unit_id', $headEmployee->unit_id)
              ->where('is_head', '!=', true)
              ->where('is_divisionhead', '!=', true)
              ->where('is_vp', '!=', true)
              ->where('is_president', '!=', true);
    })
    ->where('head_approved', null)
    ->get();

echo "Number of travel orders found: " . $travelOrders->count() . "\n";

foreach ($travelOrders as $order) {
    echo "- Travel Order ID: " . $order->id . " for employee " . $order->employee->first_name . " " . $order->employee->last_name . "\n";
}