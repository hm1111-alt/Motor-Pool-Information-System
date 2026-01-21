<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check if there's a division head for Division ID 18
$divisionHead = \App\Models\Employee::whereHas('positions', function($query) {
    $query->where('is_division_head', true)
          ->where('is_primary', true)
          ->where('division_id', 18);
})->with('positions')->first();

if ($divisionHead) {
    echo 'Found division head for Division ID 18:' . PHP_EOL;
    echo 'Name: ' . $divisionHead->first_name . ' ' . $divisionHead->last_name . PHP_EOL;
    $primaryPos = $divisionHead->positions->firstWhere('is_primary', true);
    if ($primaryPos) {
        echo 'Position: ' . $primaryPos->position_name . PHP_EOL;
        echo 'Division ID: ' . $primaryPos->division_id . PHP_EOL;
        echo 'is_divisionhead attribute: ' . ($divisionHead->is_divisionhead ? 'Yes' : 'No') . PHP_EOL;
    }
} else {
    echo 'No division head found for Division ID 18' . PHP_EOL;
}
?>