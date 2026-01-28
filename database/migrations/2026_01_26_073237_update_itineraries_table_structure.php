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
        // First, let's just modify the table structure without worrying about foreign keys initially
        Schema::table('itineraries', function (Blueprint $table) {
            // Drop the old columns that are not needed
            $droppedColumns = [
                'title', 
                'description', 
                'date', 
                'start_time', 
                'end_time', 
                'destination', 
                'purpose', 
                'status',
                'created_by'
            ];
            
            // Check which columns exist before dropping them
            $existingColumns = Schema::getColumnListing('itineraries');
            $columnsToDrop = array_intersect($droppedColumns, $existingColumns);
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        // Add the new columns as requested
        Schema::table('itineraries', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('itineraries', 'travel_order_id')) {
                $table->unsignedBigInteger('travel_order_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('itineraries', 'date_from')) {
                $table->date('date_from')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'date_to')) {
                $table->date('date_to')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'departure_time')) {
                $table->time('departure_time')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'status')) {
                $table->string('status')->default('pending');
            }
            
            // Don't add foreign keys as they might already exist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new columns
        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropColumn(['travel_order_id', 'date_from', 'date_to', 'departure_time', 'status']);
        });

        // Restore the old columns
        Schema::table('itineraries', function (Blueprint $table) {
            if (!Schema::hasColumn('itineraries', 'title')) {
                $table->string('title')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'date')) {
                $table->date('date')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'start_time')) {
                $table->time('start_time')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'end_time')) {
                $table->time('end_time')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'destination')) {
                $table->string('destination')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'purpose')) {
                $table->string('purpose')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'status')) {
                $table->string('status')->default('pending')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
        });
    }
};