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
            // Drop unnecessary columns
            $columnsToDrop = [
                'travel_order_id',
                'vp_approved',
                'vp_approved_at',
                'vp_declined',
                'vp_declined_at',
                'trip_document'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('trip_tickets', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Ensure only required columns remain: id, itinerary_id, status
            if (!Schema::hasColumn('trip_tickets', 'status')) {
                $table->enum('status', ['Pending', 'Issued', 'Completed', 'Cancelled'])->default('Pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_tickets', function (Blueprint $table) {
            // Restore the dropped columns
            $table->unsignedBigInteger('travel_order_id')->nullable();
            $table->boolean('vp_approved')->nullable();
            $table->dateTime('vp_approved_at')->nullable();
            $table->boolean('vp_declined')->nullable();
            $table->dateTime('vp_declined_at')->nullable();
            $table->string('trip_document')->nullable();
            
            // Drop the status column
            $table->dropColumn(['status']);
        });
    }
};
