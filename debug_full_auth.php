<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;
use App\Models\TravelOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

echo "=== Full Authentication & Authorization Debug ===\n\n";

try {
    // Get all employees with officer records to understand the role distribution
    $allEmployees = Employee::with('officer')->get();
    
    echo "Total employees: " . $allEmployees->count() . "\n";
    
    // Count different role types
    $heads = $allEmployees->filter(function($emp) { return $emp->is_head; });
    $divisionHeads = $allEmployees->filter(function($emp) { return $emp->is_divisionhead; });
    $vps = $allEmployees->filter(function($emp) { return $emp->is_vp; });
    $presidents = $allEmployees->filter(function($emp) { return $emp->is_president; });
    
    echo "Heads: " . $heads->count() . "\n";
    echo "Division Heads: " . $divisionHeads->count() . "\n";
    echo "VPs: " . $vps->count() . "\n";
    echo "Presidents: " . $presidents->count() . "\n\n";
    
    // Show division heads details
    echo "Division Heads Details:\n";
    foreach ($divisionHeads as $dh) {
        echo "  - {$dh->first_name} {$dh->last_name} (ID: {$dh->id})\n";
        echo "    Division ID: {$dh->division_id}\n";
        echo "    Office ID: {$dh->office_id}\n";
        if ($dh->officer) {
            echo "    Officer: unit_head={$dh->officer->unit_head}, division_head={$dh->officer->division_head}, vp={$dh->officer->vp}, president={$dh->officer->president}\n";
        }
        echo "\n";
    }
    
    // Check for travel orders that need division head approval
    $pendingDivisionApproval = TravelOrder::where('head_approved', true)
                                          ->whereNull('divisionhead_approved')
                                          ->whereHas('employee')
                                          ->with('employee')
                                          ->get();
    
    echo "Travel orders pending division head approval: " . $pendingDivisionApproval->count() . "\n";
    
    foreach ($pendingDivisionApproval as $order) {
        echo "  - Order ID: {$order->id}\n";
        echo "    Employee: {$order->employee->first_name} {$order->employee->last_name}\n";
        echo "    Employee is_head: " . ($order->employee->is_head ? 'true' : 'false') . "\n";
        echo "    Employee is_divisionhead: " . ($order->employee->is_divisionhead ? 'true' : 'false') . "\n";
        echo "    Employee is_vp: " . ($order->employee->is_vp ? 'true' : 'false') . "\n";
        echo "    Employee is_president: " . ($order->employee->is_president ? 'true' : 'false') . "\n";
        echo "    Employee Division ID: {$order->employee->division_id}\n";
        echo "    Head approved: " . ($order->head_approved ? 'true' : 'false') . "\n";
        echo "    Division head approved: " . ($order->divisionhead_approved === null ? 'null' : ($order->divisionhead_approved ? 'true' : 'false')) . "\n";
        echo "    Status: {$order->status}\n";
        echo "\n";
    }
    
    // Test the query used in DivisionHeadTravelOrderController
    if ($divisionHeads->count() > 0) {
        $testDivisionHead = $divisionHeads->first();
        echo "Testing DivisionHeadTravelOrderController query with division head: {$testDivisionHead->first_name} {$testDivisionHead->last_name}\n";
        
        $queryResult = TravelOrder::whereHas('employee', function ($query) use ($testDivisionHead) {
            $query->where('division_id', $testDivisionHead->division_id)
                  ->where(function ($subQuery) {
                      $subQuery->whereDoesntHave('officer') // Regular employees without officer records
                            ->orWhereHas('officer', function ($officerQuery) {
                                $officerQuery->where(function ($innerQuery) {
                                    $innerQuery->where('unit_head', true)  // Unit heads
                                          ->where('division_head', false)
                                          ->where('vp', false)
                                          ->where('president', false);
                                })
                                ->orWhere(function ($innerQuery) {
                                    $innerQuery->where('unit_head', false)  // Regular employees with officer records but no leadership roles
                                          ->where('division_head', false)
                                          ->where('vp', false)
                                          ->where('president', false);
                                });
                            });
                  });
        })
        ->where('head_approved', true)  // Already approved by head
        ->where('divisionhead_approved', null)   // Not yet approved by division head
        ->get();
        
        echo "Query result count: " . $queryResult->count() . "\n";
        foreach ($queryResult as $order) {
            echo "  - Order ID: {$order->id}, Employee: {$order->employee->first_name} {$order->employee->last_name}\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}