<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Employee data array
        $employees = [
            [
                'email' => 'employee@example.com',
                'first_name' => 'Employee',
                'last_name' => 'User',
                'middle_initial' => 'E',
                'full_name' => 'Employee E. User',
                'full_name2' => 'User, Employee E.',
                'sex' => 'Male',
                'prefix' => 'Mr.',
                'position_name' => 'Staff',
            ],
            [
                'email' => 'maria.santos@example.com',
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'middle_initial' => 'C',
                'full_name' => 'Maria C. Santos',
                'full_name2' => 'Santos, Maria C.',
                'sex' => 'Female',
                'prefix' => 'Ms.',
                'position_name' => 'Administrative Assistant',
            ],
            [
                'email' => 'juan.delacruz@example.com',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'middle_initial' => 'A',
                'full_name' => 'Juan A. Dela Cruz',
                'full_name2' => 'Dela Cruz, Juan A.',
                'sex' => 'Male',
                'prefix' => 'Mr.',
                'position_name' => 'Research Analyst',
            ],
            [
                'email' => 'ana.reyes@example.com',
                'first_name' => 'Ana',
                'last_name' => 'Reyes',
                'middle_initial' => 'M',
                'full_name' => 'Ana M. Reyes',
                'full_name2' => 'Reyes, Ana M.',
                'sex' => 'Female',
                'prefix' => 'Ms.',
                'position_name' => 'Project Coordinator',
            ],
            [
                'email' => 'carlos.garcia@example.com',
                'first_name' => 'Carlos',
                'last_name' => 'Garcia',
                'middle_initial' => 'B',
                'full_name' => 'Carlos B. Garcia',
                'full_name2' => 'Garcia, Carlos B.',
                'sex' => 'Male',
                'prefix' => 'Mr.',
                'position_name' => 'Financial Specialist',
            ],
        ];

        // Create employee records for each user
        foreach ($employees as $employeeData) {
            // Get the employee user
            $employeeUser = User::where('email', $employeeData['email'])->first();
            
            // If the employee user exists and doesn't already have an employee record
            if ($employeeUser && !$employeeUser->employee) {
                $employee = Employee::create([
                    'user_id' => $employeeUser->id,
                    'first_name' => $employeeData['first_name'],
                    'last_name' => $employeeData['last_name'],
                    'middle_initial' => $employeeData['middle_initial'],
                    'ext_name' => null,
                    'full_name' => $employeeData['full_name'],
                    'full_name2' => $employeeData['full_name2'],
                    'sex' => $employeeData['sex'],
                    'prefix' => $employeeData['prefix'],
                    'emp_status' => 1,
                    'class_id' => null,
                    'position_name' => $employeeData['position_name'],
                    'office_id' => null,
                    'division_id' => null,
                    'unit_id' => null,
                    'subunit_id' => null,
                ]);
            }
        }
    }
}