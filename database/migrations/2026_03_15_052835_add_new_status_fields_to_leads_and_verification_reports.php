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
            $table->boolean('discom_pms_portal_login_done')->default(false)->after('is_document_done');
            $table->boolean('bank_login_done')->default(false)->after('discom_pms_portal_login_done');
        });

        Schema::table('verification_reports', function (Blueprint $table) {
            $table->string('verified_by_manual')->nullable()->after('verified_by');
            $table->boolean('second_tier_payment_received')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['discom_pms_portal_login_done', 'bank_login_done']);
        });

        Schema::table('verification_reports', function (Blueprint $table) {
            $table->dropColumn(['verified_by_manual', 'second_tier_payment_received']);
        });
    }
};
