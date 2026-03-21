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
        Schema::create('website_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('mobile')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('type')->default('contact'); // contact, quotation
            $table->string('status')->default('pending'); // pending, replied, closed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_enquiries');
    }
};
