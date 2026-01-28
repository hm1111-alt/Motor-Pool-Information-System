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
            if (!Schema::hasColumn('itineraries', 'unit_head_approved')) {
                $table->boolean('unit_head_approved')->default(false);
            }
            if (!Schema::hasColumn('itineraries', 'unit_head_approved_by')) {
                $table->unsignedBigInteger('unit_head_approved_by')->nullable();
                $table->foreign('unit_head_approved_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('itineraries', 'unit_head_approved_at')) {
                $table->timestamp('unit_head_approved_at')->nullable();
            }
            if (!Schema::hasColumn('itineraries', 'vp_approved')) {
                $table->boolean('vp_approved')->default(false);
            }
            if (!Schema::hasColumn('itineraries', 'vp_approved_by')) {
                $table->unsignedBigInteger('vp_approved_by')->nullable();
                $table->foreign('vp_approved_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('itineraries', 'vp_approved_at')) {
                $table->timestamp('vp_approved_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            if (Schema::hasColumn('itineraries', 'vp_approved_at')) {
                $table->dropColumn('vp_approved_at');
            }
            if (Schema::hasColumn('itineraries', 'vp_approved_by')) {
                $table->dropForeign(['vp_approved_by']);
                $table->dropColumn('vp_approved_by');
            }
            if (Schema::hasColumn('itineraries', 'vp_approved')) {
                $table->dropColumn('vp_approved');
            }
            if (Schema::hasColumn('itineraries', 'unit_head_approved_at')) {
                $table->dropColumn('unit_head_approved_at');
            }
            if (Schema::hasColumn('itineraries', 'unit_head_approved_by')) {
                $table->dropForeign(['unit_head_approved_by']);
                $table->dropColumn('unit_head_approved_by');
            }
            if (Schema::hasColumn('itineraries', 'unit_head_approved')) {
                $table->dropColumn('unit_head_approved');
            }
        });
    }
};
