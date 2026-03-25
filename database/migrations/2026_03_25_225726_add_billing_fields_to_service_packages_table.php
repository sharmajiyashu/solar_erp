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
        Schema::table('service_packages', function (Blueprint $table) {
            $table->string('frequency')->default('30_days')->after('description'); // 7_days, 15_days, 30_days
            $table->string('duration_type')->default('monthly')->after('frequency'); // monthly, 3_months, 6_months, 9_months, 12_months
            $table->string('package_type')->default('subscription')->after('duration_type'); // one_time, subscription
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_packages', function (Blueprint $table) {
            //
        });
    }
};
