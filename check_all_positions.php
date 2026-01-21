<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$vpPositions = \App\Models\EmpPosition::where('is_vp', true)->with('employee')->get();
echo 'Positions with is_vp = true: ' . $vpPositions->count() . PHP_EOL;

$presidentPositions = \App\Models\EmpPosition::where('is_president', true)->with('employee')->get();
echo 'Positions with is_president = true: ' . $presidentPositions->count() . PHP_EOL;

$unitHeadPositions = \App\Models\EmpPosition::where('is_unit_head', true)->with('employee')->get();
echo 'Positions with is_unit_head = true: ' . $unitHeadPositions->count() . PHP_EOL;

// Also check for primary positions of each type
$primaryVpPositions = \App\Models\EmpPosition::where('is_primary', true)
    ->where('is_vp', true)
    ->with('employee')
    ->get();
echo PHP_EOL . 'Primary positions that are VPs: ' . $primaryVpPositions->count() . PHP_EOL;

$primaryPresidentPositions = \App\Models\EmpPosition::where('is_primary', true)
    ->where('is_president', true)
    ->with('employee')
    ->get();
echo 'Primary positions that are Presidents: ' . $primaryPresidentPositions->count() . PHP_EOL;

$primaryUnitHeadPositions = \App\Models\EmpPosition::where('is_primary', true)
    ->where('is_unit_head', true)
    ->with('employee')
    ->get();
echo 'Primary positions that are Unit Heads: ' . $primaryUnitHeadPositions->count() . PHP_EOL;