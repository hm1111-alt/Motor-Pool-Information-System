<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;
use App\Models\Officer;

echo "=== Adding Missing Officer Record ===\n\n";

try {
    // Find Roy Searca Jose Dela Cruz (Employee ID 27)
    $royEmployee = Employee::find(27);
    
    if ($royEmployee) {
        echo "Found employee: {$royEmployee->first_name} {$royEmployee->last_name} (ID: {$royEmployee->id})\n";
        
        // Check if he already has an officer record
        $existingOfficer = Officer::where('employee_id', $royEmployee->id)->first();
        
        if ($existingOfficer) {
            echo "Officer record already exists:\n";
            echo "  unit_head: " . ($existingOfficer->unit_head ? 'true' : 'false') . "\n";
            echo "  division_head: " . ($existingOfficer->division_head ? 'true' : 'false') . "\n";
            echo "  vp: " . ($existingOfficer->vp ? 'true' : 'false') . "\n";
            echo "  president: " . ($existingOfficer->president ? 'true' : 'false') . "\n";
        } else {
            // Create the officer record for division head
            $officer = Officer::create([
                'employee_id' => $royEmployee->id,
                'unit_head' => false,
                'division_head' => true,  // Set as division head
                'vp' => false,
                'president' => false,
            ]);
            
            echo "Created new officer record for Roy Searca Jose Dela Cruz:\n";
            echo "  unit_head: " . ($officer->unit_head ? 'true' : 'false') . "\n";
            echo "  division_head: " . ($officer->division_head ? 'true' : 'false') . "\n";
            echo "  vp: " . ($officer->vp ? 'true' : 'false') . "\n";
            echo "  president: " . ($officer->president ? 'true' : 'false') . "\n";
        }
    } else {
        echo "Employee with ID 27 not found.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}