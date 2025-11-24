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
            $table->boolean('president_approved')->nullable()->after('vp_declined_at');
            $table->boolean('president_declined')->nullable()->after('president_approved');
            $table->timestamp('president_approved_at')->nullable()->after('president_declined');
            $table->timestamp('president_declined_at')->nullable()->after('president_approved_at');
            $table->unsignedBigInteger('president_approved_by')->nullable()->after('president_declined_at');
            $table->unsignedBigInteger('president_declined_by')->nullable()->after('president_approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->dropColumn(['president_approved', 'president_declined', 'president_approved_at', 'president_declined_at', 'president_approved_by', 'president_declined_by']);
        });
    }
};