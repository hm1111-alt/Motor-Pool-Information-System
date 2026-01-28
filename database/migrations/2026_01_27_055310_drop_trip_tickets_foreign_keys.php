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
        Schema::table('trip_tickets', function (Blueprint $table) {
            // Drop foreign key constraints
            try {
                $table->dropForeign(['travel_order_id']);
            } catch (\Exception $e) {
                // Foreign key may not exist, continue
            }
            
            try {
                $table->dropForeign(['itinerary_id']);
            } catch (\Exception $e) {
                // Foreign key may not exist, continue
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_tickets', function (Blueprint $table) {
            // Restore foreign key constraints if needed
            // Note: We won't restore them here as the main migration will handle that
        });
    }
};
