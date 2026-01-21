<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

echo "=== Simulating Authenticated User as Division Head ===\n\n";

try {
    // Get a known division head
    $divisionHeadEmployee = Employee::whereHas('officer', function($query) {
        $query->where('division_head', true);
    })->first();
    
    if (!$divisionHeadEmployee) {
        echo "No division head found in the database.\n";
        exit;
    }
    
    echo "Found division head: {$divisionHeadEmployee->first_name} {$divisionHeadEmployee->last_name} (ID: {$divisionHeadEmployee->id})\n";
    echo "is_divisionhead: " . ($divisionHeadEmployee->is_divisionhead ? 'true' : 'false') . "\n";
    echo "Division ID: {$divisionHeadEmployee->division_id}\n\n";
    
    // Look for the associated user
    $user = User::where('email', $divisionHeadEmployee->email ?? "employee{$divisionHeadEmployee->id}@example.com")->first();
    
    if (!$user) {
        // If no user found with that email, try to find any user that might be linked to this employee
        // Since we don't have a direct relationship defined, let's create a temporary user simulation
        $user = new User();
        $user->id = $divisionHeadEmployee->id; // Assuming same ID for simplicity
        $user->name = $divisionHeadEmployee->first_name . ' ' . $divisionHeadEmployee->last_name;
        $user->email = $divisionHeadEmployee->email ?? "employee{$divisionHeadEmployee->id}@example.com";
        $user->password = bcrypt('password'); // dummy password
        
        echo "Created temporary user for testing.\n";
    }
    
    echo "User ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n\n";
    
    // Now let's manually test the authorization logic that would happen in the controller
    echo "Testing authorization logic manually:\n";
    
    // Simulate what happens in the controller
    $employee = $divisionHeadEmployee; // This would be $user->employee in real scenario
    
    echo "Employee ID: {$employee->id}\n";
    echo "Employee is_divisionhead: " . ($employee->is_divisionhead ? 'true' : 'false') . "\n";
    
    // Test the authorization check from DivisionHeadTravelOrderController
    if (!$employee->is_divisionhead) {
        echo "AUTHORIZATION CHECK FAILED: User is not a division head\n";
        echo "Would abort(403)\n";
    } else {
        echo "AUTHORIZATION CHECK PASSED: User is a division head\n";
    }
    
    echo "\n";
    
    // Now let's check if there might be a relationship issue
    echo "Checking if employee has officer relationship loaded:\n";
    if ($employee->relationLoaded('officer')) {
        echo "Officer relationship is loaded\n";
    } else {
        echo "Officer relationship is NOT loaded, loading it...\n";
        $employee->load('officer');
    }
    
    if ($employee->officer) {
        echo "Officer record exists: unit_head={$employee->officer->unit_head}, division_head={$employee->officer->division_head}\n";
    } else {
        echo "No officer record found\n";
    }
    
    // Let's also test the virtual attribute manually
    echo "\nTesting the is_divisionhead attribute manually:\n";
    $isDivisionHeadManual = $employee->officer && $employee->officer->division_head ? true : false;
    echo "Manual calculation (officer->division_head): " . ($isDivisionHeadManual ? 'true' : 'false') . "\n";
    echo "Virtual attribute (is_divisionhead): " . ($employee->is_divisionhead ? 'true' : 'false') . "\n";
    echo "Values match: " . ($isDivisionHeadManual === $employee->is_divisionhead ? 'YES' : 'NO') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}