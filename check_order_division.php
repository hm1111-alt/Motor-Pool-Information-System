<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$travelOrder = \App\Models\TravelOrder::where('destination', 'Quezon City')->first();
if ($travelOrder && $travelOrder->position) {
    echo 'Travel order position division ID: ' . $travelOrder->position->division_id . PHP_EOL;
    if ($travelOrder->position->division) {
        echo 'Division name: ' . $travelOrder->position->division->division_name . PHP_EOL;
    }
}
?>