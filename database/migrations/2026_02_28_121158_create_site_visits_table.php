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
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')
                ->constrained('leads')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->date('visit_date');

            $table->string('roof_type')->nullable();
            $table->string('shadow_analysis')->nullable();

            $table->decimal('suggested_capacity', 8, 2)->nullable();

            $table->text('notes')->nullable();

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
        Schema::dropIfExists('site_visits');
    }
};
