<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Officer;

class OfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all employees
        $employees = Employee::all();
        
        if ($employees->isEmpty()) {
            echo "No employees found. Please seed employees first.\n";
            return;
        }
        
        // Clear existing officers
        Officer::truncate();
        
        // Assign some sample roles
        // Make the first employee a President
        if ($employees->count() > 0) {
            Officer::create([
                'employee_id' => $employees[0]->id,
                'unit_head' => false,
                'division_head' => false,
                'vp' => false,
                'president' => true,
            ]);
        }
        
        // Make the second employee a VP
        if ($employees->count() > 1) {
            Officer::create([
                'employee_id' => $employees[1]->id,
                'unit_head' => false,
                'division_head' => false,
                'vp' => true,
                'president' => false,
            ]);
        }
        
        // Make the third employee a Division Head
        if ($employees->count() > 2) {
            Officer::create([
                'employee_id' => $employees[2]->id,
                'unit_head' => false,
                'division_head' => true,
                'vp' => false,
                'president' => false,
            ]);
        }
        
        // Make the fourth employee a Unit Head
        if ($employees->count() > 3) {
            Officer::create([
                'employee_id' => $employees[3]->id,
                'unit_head' => true,
                'division_head' => false,
                'vp' => false,
                'president' => false,
            ]);
        }
        
        echo "Created sample officer assignments for " . min(4, $employees->count()) . " employees.\n";
    }
}