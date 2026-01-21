<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$travelOrders = \App\Models\TravelOrder::where('destination', 'Quezon City')->get();
echo 'Found ' . $travelOrders->count() . ' travel orders to Quezon City:' . PHP_EOL;

foreach($travelOrders as $order) {
    echo 'Order ID: ' . $order->id . PHP_EOL;
    echo '  Head Approved: ' . ($order->head_approved ? 'Yes' : ($order->head_approved === false ? 'No' : 'NULL')) . PHP_EOL;
    echo '  Division Head Approved: ' . ($order->divisionhead_approved === null ? 'Pending' : ($order->divisionhead_approved ? 'Yes' : 'No')) . PHP_EOL;
    if ($order->position) {
        echo '  Position: ' . $order->position->position_name . PHP_EOL;
    } else {
        echo '  NO POSITION ASSIGNED' . PHP_EOL;
    }
    echo PHP_EOL;
}
?>