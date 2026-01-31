<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test the full update process like in the controller
    $employee = App\Models\Employee::with(['user', 'positions'])->find(74);
    
    if ($employee) {
        echo "Found employee: " . $employee->first_name . " " . $employee->last_name . "\n";
        echo "User exists: " . ($employee->user ? 'Yes' : 'No') . "\n";
        echo "Positions count: " . $employee->positions->count() . "\n";
        
        // Simulate request data
        $requestData = [
            'first_name' => 'Evaristo',
            'last_name' => 'Abella',
            'middle_initial' => '',
            'ext_name' => '',
            'sex' => 'M',
            'prefix' => 'Dr.',
            'email' => 'evaristo.abella@clsu.edu.ph',
            'position_name' => 'University President',
            'position_role' => 'president',
            'office_id' => null,
            'division_id' => null,
            'unit_id' => null,
            'subunit_id' => null,
            'class_id' => null,
        ];
        
        // Update user if exists
        if ($employee->user) {
            echo "Updating user...\n";
            $employee->user->update([
                'name' => $requestData['first_name'] . ' ' . $requestData['last_name'],
                'email' => $requestData['email'],
            ]);
        }
        
        // Update employee
        echo "Updating employee...\n";
        $employee->update([
            'first_name' => $requestData['first_name'],
            'last_name' => $requestData['last_name'],
            'middle_initial' => $requestData['middle_initial'],
            'ext_name' => $requestData['ext_name'],
            'full_name' => $requestData['first_name'] . ' ' . $requestData['last_name'],
            'full_name2' => $requestData['last_name'] . ', ' . $requestData['first_name'],
            'sex' => $requestData['sex'],
            'prefix' => $requestData['prefix'],
            'emp_status' => 1,
        ]);
        
        // Update primary position
        $primaryPosition = $employee->positions()->where('is_primary', true)->first();
        if ($primaryPosition) {
            echo "Updating primary position...\n";
            $selectedRole = $requestData['position_role'];
            $primaryPosition->update([
                'position_name' => $requestData['position_name'],
                'office_id' => $requestData['office_id'],
                'division_id' => $requestData['division_id'],
                'unit_id' => $requestData['unit_id'],
                'subunit_id' => $requestData['subunit_id'],
                'class_id' => $requestData['class_id'],
                'is_unit_head' => $selectedRole === 'unit_head',
                'is_division_head' => $selectedRole === 'division_head',
                'is_vp' => $selectedRole === 'vp',
                'is_president' => $selectedRole === 'president',
            ]);
        }
        
        echo "Full update process completed successfully!\n";
        echo "Final name: " . $employee->first_name . " " . $employee->last_name . "\n";
        
    } else {
        echo "Employee not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>