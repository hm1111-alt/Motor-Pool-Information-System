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
        Schema::table('drivers', function (Blueprint $table) {
            // Remove unnecessary columns - keep only id, emp_id, and availability_status
            $columnsToRemove = [
                'first_name',
                'last_name', 
                'middle_initial',
                'ext_name',
                'full_name',
                'full_name2',
                'sex',
                'contact_number',
                'position',
                'official_station'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Restore the dropped columns in reverse order
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_initial', 10)->nullable();
            $table->string('ext_name', 50)->nullable();
            $table->string('full_name')->nullable();
            $table->string('full_name2')->nullable();
            $table->string('sex', 20)->nullable();
            $table->string('contact_number', 50)->nullable();
            $table->string('position')->nullable();
            $table->string('official_station')->nullable();
        });
    }
};