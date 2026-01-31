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
        Schema::create('vehicle_travel_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_ticket_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->string('head_of_party');
            $table->string('destination');
            $table->date('departure_date');
            $table->time('departure_time')->nullable();
            $table->date('arrival_date')->nullable();
            $table->time('arrival_time')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('trip_ticket_id')->references('id')->on('trip_tickets')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_travel_history');
    }
};