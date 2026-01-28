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
            // Add vehicle_id column if it doesn't exist
            if (!Schema::hasColumn('itineraries', 'vehicle_id')) {
                $table->unsignedBigInteger('vehicle_id')->nullable()->after('purpose');
            }
            
            // Add driver_id column if it doesn't exist
            if (!Schema::hasColumn('itineraries', 'driver_id')) {
                $table->unsignedBigInteger('driver_id')->nullable()->after('vehicle_id');
            }
            
            // Add foreign key constraints if they don't exist
            if (Schema::hasTable('vehicles') && !Schema::hasColumn('itineraries', 'vehicle_id')) {
                $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('set null');
            }
            
            if (Schema::hasTable('drivers') && !Schema::hasColumn('itineraries', 'driver_id')) {
                $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['driver_id']);
            $table->dropColumn(['vehicle_id', 'driver_id']);
        });
    }
};