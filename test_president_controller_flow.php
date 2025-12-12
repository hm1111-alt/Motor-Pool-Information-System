<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\PresidentOwnTravelOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Test that simulates the actual controller flow
try {
    echo "=== PRESIDENT CONTROLLER FLOW TEST ===\n\n";
    
    // Get a president employee
    $president = Employee::where('is_president', 1)->first();
    
    if (!$president) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $president->first_name . " " . $president->last_name . " (ID: " . $president->id . ")\n";
    echo "is_president: " . var_export($president->is_president, true) . "\n";
    
    // Mock the Auth facade
    Auth::shouldReceive('user')->andReturn((object)[
        'employee' => $president
    ]);
    
    // Create a request that mimics what would come from the form
    $request = Request::create('/president/travel-orders', 'POST', [
        'destination' => 'Controller Flow Test',
        'date_from' => '2025-12-30',
        'date_to' => '2025-12-31',
        'purpose' => 'Controller flow test purpose',
        '_token' => 'fake_token'
    ]);
    
    // Manually set the request method and content type to simulate a real request
    $request->setMethod('POST');
    
    echo "\nRequest data:\n";
    print_r($request->all());
    
    // Validate the request (as done in the controller)
    $request->validate([
        'destination' => 'required|string|max:255',
        'date_from' => 'required|date|before_or_equal:date_to',
        'date_to' => 'required|date|after_or_equal:date_from',
        'departure_time' => 'nullable|date_format:H:i',
        'purpose' => 'required|string|max:500',
    ]);
    
    echo "\nValidation passed\n";
    
    // Simulate what the controller does
    $user = Auth::user();
    $employee = $user->employee;
    
    echo "Authenticated employee ID: " . $employee->id . "\n";
    echo "Employee is_president: " . var_export($employee->is_president, true) . "\n";
    
    // Create the travel order exactly as the controller does
    echo "\nCreating travel order...\n";
    $travelOrder = TravelOrder::create([
        'employee_id' => $employee->id,
        'destination' => $request->destination,
        'date_from' => $request->date_from,
        'date_to' => $request->date_to,
        'departure_time' => $request->departure_time,
        'purpose' => $request->purpose,
        'status' => 'approved', // President travel orders are automatically approved
        'president_approved' => true, // Mark as approved by president
        'president_approved_at' => now(), // Set approval timestamp
    ]);
    
    echo "Travel order created with ID: " . $travelOrder->id . "\n";
    
    // Check database immediately
    echo "\n--- DATABASE CHECK ---\n";
    $dbRecord = DB::table('travel_orders')->where('id', $travelOrder->id)->first();
    echo "Database status: " . $dbRecord->status . "\n";
    echo "Database president_approved: " . var_export($dbRecord->president_approved, true) . "\n";
    echo "Database president_approved_at: " . $dbRecord->president_approved_at . "\n";
    
    // Check model
    echo "\n--- MODEL CHECK ---\n";
    $travelOrder->refresh();
    echo "Model status: " . $travelOrder->status . "\n";
    echo "Model president_approved: " . var_export($travelOrder->president_approved, true) . "\n";
    echo "Model remarks: " . $travelOrder->remarks . "\n";
    
    // Clean up
    $travelOrder->delete();
    
    echo "\nTest completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}