<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Transfer existing officer data from employees table to officers table
        DB::transaction(function () {
            // Get all employees with officer roles
            $employeesWithRoles = DB::table('employees')
                ->where(function ($query) {
                    $query->where('is_head', true)
                          ->orWhere('is_divisionhead', true)
                          ->orWhere('is_vp', true)
                          ->orWhere('is_president', true);
                })
                ->get();
            
            // Insert into officers table
            foreach ($employeesWithRoles as $employee) {
                DB::table('officers')->insert([
                    'employee_id' => $employee->id,
                    'unit_head' => (bool) $employee->is_head,
                    'division_head' => (bool) $employee->is_divisionhead,
                    'vp' => (bool) $employee->is_vp,
                    'president' => (bool) $employee->is_president,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
        
        // Remove the old boolean columns from employees table
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['is_head', 'is_divisionhead', 'is_vp', 'is_president']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the old boolean columns to employees table
        Schema::table('employees', function (Blueprint $table) {
            $table->boolean('is_head')->nullable();
            $table->boolean('is_divisionhead')->nullable();
            $table->boolean('is_vp')->nullable();
            $table->boolean('is_president')->nullable();
        });
        
        // Transfer data back from officers table to employees table
        DB::transaction(function () {
            $officers = DB::table('officers')->get();
            
            foreach ($officers as $officer) {
                DB::table('employees')
                    ->where('id', $officer->employee_id)
                    ->update([
                        'is_head' => $officer->unit_head,
                        'is_divisionhead' => $officer->division_head,
                        'is_vp' => $officer->vp,
                        'is_president' => $officer->president,
                    ]);
            }
        });
        
        // Drop the officers table
        Schema::dropIfExists('officers');
    }
};