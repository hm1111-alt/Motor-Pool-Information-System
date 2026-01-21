<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

echo "=== Checking Current User Role ===\n\n";

try {
    // Since we can't simulate an authenticated user easily in this context,
    // let's check all employees who are division heads to see what their attributes look like
    $divisionHeads = Employee::whereHas('officer', function($query) {
        $query->where('division_head', true);
    })->get();
    
    echo "Found " . count($divisionHeads) . " division heads:\n\n";
    
    foreach ($divisionHeads as $dh) {
        echo "Division Head: {$dh->first_name} {$dh->last_name}\n";
        echo "  ID: {$dh->id}\n";
        echo "  Division ID: {$dh->division_id}\n";
        echo "  is_head: " . ($dh->is_head ? 'true' : 'false') . "\n";
        echo "  is_divisionhead: " . ($dh->is_divisionhead ? 'true' : 'false') . "\n";
        echo "  is_vp: " . ($dh->is_vp ? 'true' : 'false') . "\n";
        echo "  is_president: " . ($dh->is_president ? 'true' : 'false') . "\n";
        if ($dh->officer) {
            echo "  Officer Record: unit_head={$dh->officer->unit_head}, division_head={$dh->officer->division_head}, vp={$dh->officer->vp}, president={$dh->officer->president}\n";
        }
        echo "\n";
    }
    
    // Also check some regular employees for comparison
    echo "Sample regular employees for comparison:\n\n";
    $regularEmployees = Employee::limit(3)->get();
    foreach ($regularEmployees as $re) {
        echo "Employee: {$re->first_name} {$re->last_name}\n";
        echo "  ID: {$re->id}\n";
        echo "  is_head: " . ($re->is_head ? 'true' : 'false') . "\n";
        echo "  is_divisionhead: " . ($re->is_divisionhead ? 'true' : 'false') . "\n";
        echo "  is_vp: " . ($re->is_vp ? 'true' : 'false') . "\n";
        echo "  is_president: " . ($re->is_president ? 'true' : 'false') . "\n";
        if ($re->officer) {
            echo "  Officer Record: unit_head={$re->officer->unit_head}, division_head={$re->officer->division_head}, vp={$re->officer->vp}, president={$re->officer->president}\n";
        } else {
            echo "  No officer record\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}