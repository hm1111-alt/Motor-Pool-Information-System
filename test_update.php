<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test updating employee directly
    $employee = App\Models\Employee::find(74);
    
    if ($employee) {
        echo "Found employee: " . $employee->first_name . " " . $employee->last_name . "\n";
        
        // Test simple update
        $result = $employee->update([
            'first_name' => 'Evaristo',
            'last_name' => 'Abella',
            'sex' => 'M'
        ]);
        
        if ($result) {
            echo "Update successful!\n";
            echo "Updated name: " . $employee->first_name . " " . $employee->last_name . "\n";
        } else {
            echo "Update failed\n";
        }
    } else {
        echo "Employee not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>