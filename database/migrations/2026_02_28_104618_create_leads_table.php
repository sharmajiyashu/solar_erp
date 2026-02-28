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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->string('lead_no')->unique();

            // Optional Enquiry
            $table->foreignId('enquiry_id')
                ->nullable()
                ->constrained('enquiries')
                ->nullOnDelete();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');

            // Created By
            $table->foreignId('created_by')
                ->constrained('users')
                ->onDelete('cascade');

            // Current active stage
            $table->string('stage')->default('pending_lead');

            // Overall status
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->json('project_stages')->nullable();

            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
