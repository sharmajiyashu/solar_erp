<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->unsignedSmallInteger('total_slots')->nullable()->after('end_date');
            $table->unsignedTinyInteger('duration_months')->nullable()->after('total_slots');
        });

        Schema::table('service_slots', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('subscription_id')->constrained()->cascadeOnDelete();
            $table->string('verification_code', 6)->nullable()->after('status');
            $table->foreignId('assigned_to')->nullable()->after('verification_code')->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable()->after('assigned_to');
            $table->timestamp('completed_at')->nullable()->after('assigned_at');
        });

        DB::table('service_slots')->orderBy('id')->chunkById(100, function ($slots) {
            foreach ($slots as $slot) {
                $userId = DB::table('user_subscriptions')->where('id', $slot->subscription_id)->value('user_id');
                $code = $this->makeUniqueCode();
                DB::table('service_slots')->where('id', $slot->id)->update([
                    'user_id' => $userId,
                    'verification_code' => $code,
                ]);
            }
        });

        Schema::table('service_slots', function (Blueprint $table) {
            $table->index(['status', 'service_date']);
            $table->unique('verification_code');
        });

        Schema::create('service_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_slot_id')->constrained('service_slots')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique('service_slot_id');
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_slot_id')->constrained('service_slots')->cascadeOnDelete();
            $table->string('subject');
            $table->string('status', 32)->default('open');
            $table->foreignId('assigned_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_admin')->default(false);
            $table->text('body');
            $table->timestamps();
            $table->index('ticket_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('fcm_token')->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });

        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('service_feedback');

        Schema::table('service_slots', function (Blueprint $table) {
            $table->dropUnique(['verification_code']);
            $table->dropIndex(['status', 'service_date']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('assigned_to');
            $table->dropColumn(['verification_code', 'assigned_at', 'completed_at']);
        });

        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['total_slots', 'duration_months']);
        });
    }

    private function makeUniqueCode(): string
    {
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (DB::table('service_slots')->where('verification_code', $code)->exists());

        return $code;
    }
};
