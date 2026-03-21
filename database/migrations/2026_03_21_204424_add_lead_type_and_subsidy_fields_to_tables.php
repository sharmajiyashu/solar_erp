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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('lead_type')->default('cash')->after('status');
        });

        Schema::table('verification_reports', function (Blueprint $table) {
            $table->boolean('is_subsidy_received')->default(false)->after('second_tier_payment_received');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('lead_type');
        });

        Schema::table('verification_reports', function (Blueprint $table) {
            $table->dropColumn('is_subsidy_received');
        });
    }
};
