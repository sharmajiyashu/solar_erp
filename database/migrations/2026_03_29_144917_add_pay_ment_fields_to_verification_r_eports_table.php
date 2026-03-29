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
            $table->decimal('quotation_price', 15, 2)->nullable();
            $table->date('first_tranche_date')->nullable();
            $table->decimal('second_tranche_amount', 15, 2)->nullable();
            $table->decimal('tax_invoice_amount', 15, 2)->nullable();
            $table->decimal('payout_amount', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verification_reports', function (Blueprint $table) {
            $table->dropColumn(['quotation_price', 'first_tranche_date', 'second_tranche_amount', 'tax_invoice_amount', 'payout_amount']);
        });
    }
};
