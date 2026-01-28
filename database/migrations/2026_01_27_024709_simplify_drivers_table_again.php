<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Check if a foreign key constraint exists.
     */
    private function hasForeignKey(string $table, string $foreignKey): bool
    {
        $result = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE CONSTRAINT_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND CONSTRAINT_NAME = ?
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ", [$table, $foreignKey]);
        
        return count($result) > 0;
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // First drop foreign key constraints if they exist
            // Check if foreign keys exist before attempting to drop them
            $foreignKeys = DB::select(
                "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'drivers' AND CONSTRAINT_TYPE = 'FOREIGN KEY'"
            );
            
            $existingConstraints = [];
            foreach ($foreignKeys as $fk) {
                $existingConstraints[] = $fk->CONSTRAINT_NAME;
            }
            
            // Drop foreign keys only if they exist, using the column name syntax
            if (in_array('drivers_emp_id_foreign', $existingConstraints)) {
                $table->dropForeign(['emp_id']);
            }
            if (in_array('drivers_class_id_foreign', $existingConstraints)) {
                $table->dropForeign(['class_id']);
            }
            if (in_array('drivers_office_id_foreign', $existingConstraints)) {
                $table->dropForeign(['office_id']);
            }
            if (in_array('drivers_division_id_foreign', $existingConstraints)) {
                $table->dropForeign(['division_id']);
            }
            if (in_array('drivers_unit_id_foreign', $existingConstraints)) {
                $table->dropForeign(['unit_id']);
            }
            if (in_array('drivers_subunit_id_foreign', $existingConstraints)) {
                $table->dropForeign(['subunit_id']);
            }
            
            // Remove unnecessary columns
            $columnsToRemove = [
                'emp_id',
                'class_id',
                'office_id',
                'division_id',
                'unit_id',
                'subunit_id',
                'contact_number',
                'position_name'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('drivers', 'full_name')) {
                $table->string('full_name')->nullable();
            }
            
            if (!Schema::hasColumn('drivers', 'full_name2')) {
                $table->string('full_name2')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Restore the dropped columns
            $table->unsignedBigInteger('emp_id')->nullable();
            $table->string('position_name')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('subunit_id')->nullable();
            $table->string('contact_number', 50)->nullable();
            
            // Drop added columns
            $table->dropColumn(['full_name', 'full_name2']);
            
            // Re-add foreign key constraints
            if (!$this->hasForeignKey('drivers', 'drivers_emp_id_foreign')) {
                $table->foreign('emp_id')->references('id')->on('employees')->onDelete('set null');
            }
            if (!$this->hasForeignKey('drivers', 'drivers_class_id_foreign')) {
                $table->foreign('class_id')->references('id')->on('class')->onDelete('set null');
            }
            if (!$this->hasForeignKey('drivers', 'drivers_office_id_foreign')) {
                $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null');
            }
            if (!$this->hasForeignKey('drivers', 'drivers_division_id_foreign')) {
                $table->foreign('division_id')->references('id')->on('divisions')->onDelete('set null');
            }
            if (!$this->hasForeignKey('drivers', 'drivers_unit_id_foreign')) {
                $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            }
            if (!$this->hasForeignKey('drivers', 'drivers_subunit_id_foreign')) {
                $table->foreign('subunit_id')->references('id')->on('subunits')->onDelete('set null');
            }
            if (!$this->hasForeignKey('drivers', 'drivers_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }
};