<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$travelOrders = \App\Models\TravelOrder::whereNull('divisionhead_approved')
    ->where('head_approved', true)
    ->with('position', 'position.division')
    ->get();
    
echo 'Found ' . $travelOrders->count() . ' travel orders needing division head approval:' . PHP_EOL;

foreach($travelOrders as $order) {
    echo 'Order: ' . $order->destination . ' - Position Division ID: ';
    if ($order->position) {
        echo $order->position->division_id;
        if ($order->position->division) {
            echo ' (' . $order->position->division->division_name . ')';
        }
    } else {
        echo 'NO POSITION ASSIGNED';
    }
    echo PHP_EOL;
}
?>