<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create pivot table for employee-office relationships
        Schema::create('employee_offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('office_id')->constrained('offices')->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->unique(['employee_id', 'office_id']);
        });
        
        // Create pivot table for employee-division relationships
        Schema::create('employee_divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('division_id')->constrained('divisions')->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->unique(['employee_id', 'division_id']);
        });
        
        // Create pivot table for employee-unit relationships
        Schema::create('employee_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->unique(['employee_id', 'unit_id']);
        });
        
        // Create pivot table for employee-subunit relationships
        Schema::create('employee_subunits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('subunit_id')->constrained('subunits')->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->unique(['employee_id', 'subunit_id']);
        });
        
        // Create pivot table for employee-class relationships (to support multiple classes)
        Schema::create('employee_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('class')->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->unique(['employee_id', 'class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_classes');
        Schema::dropIfExists('employee_subunits');
        Schema::dropIfExists('employee_units');
        Schema::dropIfExists('employee_divisions');
        Schema::dropIfExists('employee_offices');
    }
};