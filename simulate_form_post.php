<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use App\Models\User;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate a form post to the create endpoint
echo "Simulating form submission to create employee...\n";

// Sample form data (similar to what would be sent from the browser)
$formData = [
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'johndoe' . time() . '@example.com', // Use unique email each time
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'position_name' => 'Test Position',
    'sex' => 'M',
    'role' => 'vp', // Select a role
    'emp_status' => '1', // This should be sent from the hidden field
];

echo "Form data being submitted:\n";
foreach ($formData as $key => $value) {
    if ($key === 'password' || $key === 'password_confirmation') {
        echo "  $key: ***hidden***\n";
    } else {
        echo "  $key: $value\n";
    }
}
echo "\n";

try {
    // Create a request object like Laravel would from a form post
    $request = new Request($formData);
    $request->setMethod('POST');
    
    // Create controller instance
    $controller = new EmployeeController();
    
    // Call the store method
    $response = $controller->store($request);
    
    echo "Controller method executed successfully!\n";
    echo "Response type: " . get_class($response) . "\n";
    
    // Check if it's a redirect response
    if ($response instanceof \Illuminate\Http\RedirectResponse) {
        echo "Redirect URL: " . $response->getTargetUrl() . "\n";
        echo "Session data:\n";
        
        $session = $response->getSession();
        if ($session) {
            $flashData = $session->get('success');
            if ($flashData) {
                echo "  Success message: " . $flashData . "\n";
            } else {
                echo "  No success message in session\n";
            }
        }
    }
    
    // Verify that the employee was actually created
    $user = User::where('email', $formData['email'])->first();
    if ($user) {
        echo "\n✅ User created successfully with ID: " . $user->id . "\n";
        
        $employee = Employee::where('user_id', $user->id)->first();
        if ($employee) {
            echo "✅ Employee created successfully with ID: " . $employee->id . "\n";
            echo "  Name: " . $employee->first_name . " " . $employee->last_name . "\n";
            echo "  Status: " . ($employee->emp_status ? 'Active' : 'Inactive') . "\n";
            
            if ($employee->officer) {
                echo "✅ Officer record created with ID: " . $employee->officer->id . "\n";
                echo "  Role assigned: ";
                if ($employee->officer->unit_head) echo "Unit Head\n";
                elseif ($employee->officer->division_head) echo "Division Head\n";
                elseif ($employee->officer->vp) echo "VP\n";
                elseif ($employee->officer->president) echo "President\n";
                else echo "No role\n";
            } else {
                echo "⚠️ No officer record created\n";
            }
        } else {
            echo "❌ No employee record found\n";
        }
    } else {
        echo "❌ User was not created\n";
    }
    
    // Clean up test data
    if (isset($user) && $user) {
        if ($employee && $employee->officer) {
            $employee->officer->delete();
        }
        if ($employee) {
            $employee->delete();
        }
        $user->delete();
        echo "\n🧹 Test data cleaned up successfully\n";
    }

} catch (Exception $e) {
    echo "❌ Exception occurred: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    
    // If it's a validation error, let's see what field failed
    if ($e instanceof Illuminate\Validation\ValidationException) {
        echo "Validation errors:\n";
        foreach ($e->errors() as $field => $messages) {
            foreach ($messages as $message) {
                echo "  $field: $message\n";
            }
        }
    }
}