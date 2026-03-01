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
        Schema::create('dispatch_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('transporter_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('driver_contact')->nullable();

            $table->date('dispatch_date')->nullable();

            $table->string('status')->default('packed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_details');
    }
};
