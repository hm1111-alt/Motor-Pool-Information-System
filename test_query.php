<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\TravelOrder;

// Test the query
$headEmployee = Employee::find(13); // Anjela Tolentino is the head of unit 104

echo "Head Employee: " . $headEmployee->first_name . " " . $headEmployee->last_name . " (Unit ID: " . $headEmployee->unit_id . ")\n";

// This is the exact query from HeadTravelOrderController
$travelOrders = TravelOrder::whereHas('employee', function ($query) use ($headEmployee) {
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
    echo "- Travel Order ID: " . $order->id . " for employee " . $order->employee->first_name . " " . $order->employee->last_name . " (Head Approved: " . var_export($order->head_approved, true) . ")\n";
}