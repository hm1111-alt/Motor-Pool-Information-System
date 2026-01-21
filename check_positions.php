<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$positions = \App\Models\EmpPosition::where('is_division_head', true)->with('employee')->get();
echo 'Positions with is_division_head = true: ' . $positions->count() . PHP_EOL;
foreach($positions as $pos) {
    echo '- Position: ' . $pos->position_name . ' - Employee: ' . ($pos->employee ? $pos->employee->first_name . ' ' . $pos->employee->last_name : 'None') . PHP_EOL;
}

// Also check for primary positions that are division heads
$primaryDivisionHeadPositions = \App\Models\EmpPosition::where('is_primary', true)
    ->where('is_division_head', true)
    ->with('employee')
    ->get();
echo PHP_EOL . 'Primary positions that are division heads: ' . $primaryDivisionHeadPositions->count() . PHP_EOL;
foreach($primaryDivisionHeadPositions as $pos) {
    echo '- Position: ' . $pos->position_name . ' - Employee: ' . ($pos->employee ? $pos->employee->first_name . ' ' . $pos->employee->last_name : 'None') . ' - Division ID: ' . $pos->division_id . PHP_EOL;
}