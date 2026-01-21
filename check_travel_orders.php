<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$travelOrders = \App\Models\TravelOrder::whereNull('divisionhead_approved')
    ->where('head_approved', true)
    ->with('employee', 'position')
    ->get();
    
foreach($travelOrders as $order) {
    if ($order->employee) {
        echo 'Travel Order: ' . $order->destination . PHP_EOL;
        echo '  Employee: ' . $order->employee->first_name . ' ' . $order->employee->last_name . PHP_EOL;
        echo '  Head Approved: ' . ($order->head_approved ? 'Yes' : 'No') . PHP_EOL;
        echo '  Division Head Approved: ' . ($order->divisionhead_approved === null ? 'Pending' : ($order->divisionhead_approved ? 'Yes' : 'No')) . PHP_EOL;
        echo '  VP Approved: ' . ($order->vp_approved === null ? 'Pending' : ($order->vp_approved ? 'Yes' : 'No')) . PHP_EOL;
        echo '  President Approved: ' . ($order->president_approved === null ? 'Pending' : ($order->president_approved ? 'Yes' : 'No')) . PHP_EOL;
        echo '  Status: ' . $order->status . PHP_EOL;
        if ($order->position) {
            echo '  Position: ' . $order->position->position_name . PHP_EOL;
            echo '  Position is Unit Head: ' . ($order->position->is_unit_head ? 'Yes' : 'No') . PHP_EOL;
            echo '  Position is Division Head: ' . ($order->position->is_division_head ? 'Yes' : 'No') . PHP_EOL;
            echo '  Position is VP: ' . ($order->position->is_vp ? 'Yes' : 'No') . PHP_EOL;
            echo '  Position is President: ' . ($order->position->is_president ? 'Yes' : 'No') . PHP_EOL;
        }
        echo PHP_EOL;
    }
}