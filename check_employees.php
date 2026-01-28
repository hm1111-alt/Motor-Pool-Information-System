<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = App\Models\User::where('role', 'employees')->get();

echo "Checking employee users:\n";
foreach($users as $user) {
    echo "User: " . $user->name . " (ID: " . $user->id . ")\n";
    $employee = $user->employee;
    if ($employee) {
        echo "  -> Has employee: " . $employee->first_name . " " . $employee->last_name . " (ID: " . $employee->id . ")\n";
    } else {
        echo "  -> No employee record\n";
    }
    echo "\n";
}