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
        Schema::create('enquiry_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enquiry_id');
            $table->unsignedBigInteger('created_by');
            $table->date('followup_date')->nullable();
            $table->time('followup_time')->nullable();
            $table->text('remarks')->nullable();
            $table->date('next_followup_date')->nullable();
            $table->enum('status', [
                'pending',
                'completed',
                'rescheduled'
            ])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiry_follow_ups');
    }
};
