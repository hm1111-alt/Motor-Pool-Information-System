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
        Schema::table('itineraries', function (Blueprint $table) {
            // Add the required columns if they don't exist
            if (!Schema::hasColumn('itineraries', 'title')) {
                $table->string('title');
            }
            if (!Schema::hasColumn('itineraries', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'date')) {
                $table->date('date');
            }
            if (!Schema::hasColumn('itineraries', 'start_time')) {
                $table->time('start_time');
            }
            if (!Schema::hasColumn('itineraries', 'end_time')) {
                $table->time('end_time');
            }
            if (!Schema::hasColumn('itineraries', 'destination')) {
                $table->string('destination');
            }
            if (!Schema::hasColumn('itineraries', 'purpose')) {
                $table->string('purpose');
            }
            if (!Schema::hasColumn('itineraries', 'vehicle_id')) {
                $table->unsignedBigInteger('vehicle_id')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'driver_id')) {
                $table->unsignedBigInteger('driver_id')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'created_by')) {
                $table->unsignedBigInteger('created_by');
            }
            if (!Schema::hasColumn('itineraries', 'status')) {
                $table->string('status')->default('pending');
            }
        });
        
        // Add foreign keys separately to avoid issues - use 'vehicles' table name
        Schema::table('itineraries', function (Blueprint $table) {
            if (!Schema::hasTable('itineraries') || 
                (!Schema::getConnection()->getDoctrineSchemaManager()->introspectTable('itineraries')->hasForeignKey('itineraries_vehicle_id_foreign') &&
                 Schema::hasTable('vehicles'))) {
                $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('set null');
            }
            if (!Schema::hasTable('itineraries') || 
                (!Schema::getConnection()->getDoctrineSchemaManager()->introspectTable('itineraries')->hasForeignKey('itineraries_driver_id_foreign') &&
                 Schema::hasTable('drivers'))) {
                $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
            }
            if (!Schema::hasTable('itineraries') || 
                (!Schema::getConnection()->getDoctrineSchemaManager()->introspectTable('itineraries')->hasForeignKey('itineraries_created_by_foreign') &&
                 Schema::hasTable('users'))) {
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints first
        Schema::table('itineraries', function (Blueprint $table) {
            try {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $doctrineTable = $sm->introspectTable('itineraries');
                
                // Check and drop foreign keys if they exist
                if ($doctrineTable->hasForeignKey('itineraries_vehicle_id_foreign')) {
                    $table->dropForeign(['vehicle_id']);
                }
                if ($doctrineTable->hasForeignKey('itineraries_driver_id_foreign')) {
                    $table->dropForeign(['driver_id']);
                }
                if ($doctrineTable->hasForeignKey('itineraries_created_by_foreign')) {
                    $table->dropForeign(['created_by']);
                }
            } catch (\Exception $e) {
                // If there's an exception, continue with dropping columns
            }
        });

        // Now drop the columns
        Schema::table('itineraries', function (Blueprint $table) {
            $columns = Schema::getColumnListing('itineraries');
            
            $columnsToDrop = [
                'title', 'description', 'date', 'start_time', 'end_time', 
                'destination', 'purpose', 'vehicle_id', 'driver_id', 'created_by', 'status'
            ];
            
            $existingColumns = array_intersect($columns, $columnsToDrop);
            
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};