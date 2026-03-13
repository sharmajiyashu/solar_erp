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
            $table->boolean('first_payment_received')->default(false)->after('remarks');
            $table->boolean('is_document_done')->default(false)->after('first_payment_received');
        });

        Schema::table('installations', function (Blueprint $table) {
            $table->boolean('installation_done')->default(false)->after('status');
            $table->boolean('net_metering_pending')->default(false)->after('installation_done');
            $table->boolean('net_metering_done')->default(false)->after('net_metering_pending');
            $table->boolean('second_tier_payment_received')->default(false)->after('net_metering_done');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['first_payment_received', 'is_document_done']);
        });

        Schema::table('installations', function (Blueprint $table) {
            $table->dropColumn([
                'installation_done',
                'net_metering_pending',
                'net_metering_done',
                'second_tier_payment_received'
            ]);
        });
    }
};
