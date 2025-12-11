<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

// Test the specific VP dashboard query
try {
    // Get the VP employee (Ravelina Velasco - ID: 9)
    $vpEmployee = Employee::find(9);
    
    if (!$vpEmployee) {
        echo "VP not found\n";
        exit;
    }
    
    echo "VP: " . $vpEmployee->first_name . " " . $vpEmployee->last_name . " (ID: " . $vpEmployee->id . ", Office ID: " . $vpEmployee->office_id . ")\n";
    
    // Build the query exactly as in the controller
    $query = TravelOrder::whereHas('employee', function ($query) use ($vpEmployee) {
        $query->where('office_id', $vpEmployee->office_id)
              ->where(function ($query) {
                  $query->where('is_president', 0)
                        ->orWhereNull('is_president');
              });
    })
    ->where(function ($query) {
        // For regular employees: head_approved must be true
        $query->whereHas('employee', function ($subQuery) {
            $subQuery->where('is_head', 0)
                      ->orWhereNull('is_head');
        })->where('head_approved', true)
        // For heads: divisionhead_approved must be true
        ->orWhereHas('employee', function ($subQuery) {
            $subQuery->where('is_head', 1);
        })->where('divisionhead_approved', true);
    })
    ->where('vp_approved', null);
    
    // Debug the query
    echo "Generated SQL: " . $query->toSql() . "\n";
    echo "Bindings: " . json_encode($query->getBindings()) . "\n\n";
    
    // Execute the query
    $travelOrders = $query->orderByRaw('(CASE WHEN travel_orders.employee_id IN (SELECT id FROM employees WHERE is_head = 1) THEN divisionhead_approved_at ELSE head_approved_at END) DESC')->get();
    
    echo "Found " . count($travelOrders) . " travel orders matching criteria:\n\n";
    
    foreach ($travelOrders as $travelOrder) {
        echo "Travel Order ID: " . $travelOrder->id . "\n";
        echo "  Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . " (ID: " . $travelOrder->employee->id . ")\n";
        echo "  Is Head: " . ($travelOrder->employee->is_head ? 'Yes' : 'No') . "\n";
        echo "  Office ID: " . $travelOrder->employee->office_id . "\n";
        echo "  Head Approved: " . var_export($travelOrder->head_approved, true) . "\n";
        echo "  Division Head Approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
        echo "  VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
        echo "  Remarks: " . $travelOrder->remarks . "\n";
        echo "---\n";
    }
    
    // Let's also manually check the specific travel order that should match
    echo "\n=== Checking Specific Travel Order (ID: 64) ===\n";
    $specificTravelOrder = TravelOrder::with('employee')->find(64);
    
    if ($specificTravelOrder) {
        echo "Employee Office ID: " . $specificTravelOrder->employee->office_id . "\n";
        echo "VP Office ID: " . $vpEmployee->office_id . "\n";
        echo "Office Match: " . ($specificTravelOrder->employee->office_id == $vpEmployee->office_id ? 'Yes' : 'No') . "\n";
        
        echo "Employee is_president: " . var_export($specificTravelOrder->employee->is_president, true) . "\n";
        echo "President Check Pass: " . (($specificTravelOrder->employee->is_president == 0 || is_null($specificTravelOrder->employee->is_president)) ? 'Yes' : 'No') . "\n";
        
        echo "Employee is_head: " . var_export($specificTravelOrder->employee->is_head, true) . "\n";
        echo "Division Head Approved: " . var_export($specificTravelOrder->divisionhead_approved, true) . "\n";
        echo "Division Head Approval Check Pass: " . ($specificTravelOrder->divisionhead_approved ? 'Yes' : 'No') . "\n";
        
        echo "VP Approved: " . var_export($specificTravelOrder->vp_approved, true) . "\n";
        echo "VP Approval Null Check Pass: " . (is_null($specificTravelOrder->vp_approved) ? 'Yes' : 'No') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}