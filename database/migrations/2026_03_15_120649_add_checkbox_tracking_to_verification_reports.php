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
        Schema::table('verification_reports', function (Blueprint $table) {
            $table->boolean('is_docs_proceed_for_2nd_tranch')->default(false)->after('verification_date');
            $table->boolean('is_verified')->default(false)->after('second_tier_payment_received');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verification_reports', function (Blueprint $table) {
            $table->dropColumn(['is_docs_proceed_for_2nd_tranch', 'is_verified']);
        });
    }
};
