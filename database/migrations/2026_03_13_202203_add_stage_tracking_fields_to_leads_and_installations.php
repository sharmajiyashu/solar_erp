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
            $table->timestamp('lead_created_at')->nullable();
            $table->timestamp('site_visit_created_at')->nullable();
            $table->timestamp('quotation_created_at')->nullable();
            $table->timestamp('bank_document_created_at')->nullable();
            $table->timestamp('material_dispatch_created_at')->nullable();
            $table->timestamp('installation_created_at')->nullable();
            $table->timestamp('verification_created_at')->nullable();
            $table->timestamp('project_completion_created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'lead_created_at',
                'site_visit_created_at',
                'quotation_created_at',
                'bank_document_created_at',
                'material_dispatch_created_at',
                'installation_created_at',
                'verification_created_at',
                'project_completion_created_at',
            ]);
        });
    }
};
