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
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->integer('emp_position_id')->nullable();
            $table->foreign('emp_position_id')->references('id')->on('emp_positions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->dropForeign(['emp_position_id']);
            $table->dropColumn('emp_position_id');
        });
    }
};
