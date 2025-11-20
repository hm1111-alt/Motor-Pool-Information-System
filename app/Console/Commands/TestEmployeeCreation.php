<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\User;

class TestEmployeeCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-employee-creation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test employee creation with email and password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Create the user first
            $user = User::create([
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => bcrypt('password123'),
                'role' => User::ROLE_EMPLOYEE,
            ]);

            // Create the employee and link to the user
            $employee = Employee::create([
                'user_id' => $user->id,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'middle_initial' => 'M',
                'full_name' => 'John Doe',
                'full_name2' => 'Doe, John',
                'sex' => 'M',
                'position_name' => 'Software Engineer',
                'emp_status' => 1,
            ]);

            $this->info("Employee created successfully!");
            $this->info("Employee ID: " . $employee->id);
            $this->info("User ID: " . $employee->user_id);
            $this->info("Employee Name: " . $employee->full_name);
            $this->info("User Email: " . $user->email);
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}