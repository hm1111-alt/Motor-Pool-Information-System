<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get existing trip ticket records
        $existingRecords = DB::table('trip_tickets')->get();
        
        // Drop the current table
        Schema::dropIfExists('trip_tickets');
        
        // Create the table with new structure
        Schema::create('trip_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('itinerary_id')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Completed', 'Cancelled'])->default('Pending');
            $table->json('passengers')->nullable();
            $table->timestamps();
            
            // Add foreign key constraint
            $table->foreign('itinerary_id')->references('id')->on('itineraries')->nullOnDelete();
        });
        
        // Restore the existing records with only the required fields
        foreach ($existingRecords as $record) {
            DB::table('trip_tickets')->insert([
                'id' => $record->id,
                'itinerary_id' => $record->itinerary_id,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
                'status' => $record->status ?? 'Pending', // Preserve status if exists
                'passengers' => $record->passengers ?? null // Add passengers column
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a complex migration, so we won't implement a full rollback
        // In production, you'd want to preserve the old data
        Schema::dropIfExists('trip_tickets');
        
        // Recreate with the old structure
        Schema::create('trip_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->nullable()->constrained('itineraries')->nullOnDelete();
            $table->foreignId('travel_order_id')->nullable()->constrained('travel_orders')->nullOnDelete();
            $table->boolean('vp_approved')->nullable();
            $table->dateTime('vp_approved_at')->nullable();
            $table->boolean('vp_declined')->nullable();
            $table->dateTime('vp_declined_at')->nullable();
            $table->enum('status', ['Approved', 'Not yet Approved', 'Cancelled'])->nullable();
            $table->string('trip_document')->nullable();
            $table->timestamps();
        });
    }
};
