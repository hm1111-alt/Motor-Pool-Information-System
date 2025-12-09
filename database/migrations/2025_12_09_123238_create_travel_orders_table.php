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
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('destination');
            $table->date('date_from');
            $table->date('date_to');
            $table->text('purpose');
            
            // Approval fields
            $table->boolean('head_approved')->default(false);
            $table->timestamp('head_approved_at')->nullable();
            $table->text('head_remarks')->nullable();
            
            $table->boolean('vp_approved')->default(false);
            $table->timestamp('vp_approved_at')->nullable();
            $table->text('vp_remarks')->nullable();
            
            // Status field with constraint
            $table->string('status')->default('pending'); // pending, approved, cancelled
            
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            
            // Indexes for better query performance
            $table->index('employee_id');
            $table->index('status');
            $table->index('head_approved');
            $table->index('vp_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_orders');
    }
};
