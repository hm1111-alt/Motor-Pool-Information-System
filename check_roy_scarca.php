<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check Roy Searca Jose Dela Cruz specifically
$roy = \App\Models\Employee::where('first_name', 'Roy')->where('last_name', 'Searca')->first();
if ($roy) {
    echo 'Roy Searca found:' . PHP_EOL;
    echo 'is_divisionhead attribute: ' . ($roy->is_divisionhead ? 'Yes' : 'No') . PHP_EOL;
    
    foreach($roy->positions as $pos) {
        echo 'Position: ' . $pos->position_name . PHP_EOL;
        echo '  is_primary: ' . ($pos->is_primary ? 'Yes' : 'No') . PHP_EOL;
        echo '  is_division_head: ' . ($pos->is_division_head ? 'Yes' : 'No') . PHP_EOL;
        echo '  division_id: ' . $pos->division_id . PHP_EOL;
    }
} else {
    echo 'Roy Searca not found' . PHP_EOL;
}
?>