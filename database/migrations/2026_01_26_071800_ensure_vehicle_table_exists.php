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
        if (!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('picture')->nullable();
                $table->string('plate_number', 50)->nullable();
                $table->string('model')->nullable();
                $table->string('type')->nullable();
                $table->integer('seating_capacity')->nullable();
                $table->integer('mileage')->nullable();
                $table->enum('status', ['Available', 'Not Available', 'Active', 'Under Maintenance'])->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};