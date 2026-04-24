<?php

namespace App\Services\Firebase;

use App\Models\TicketMessage;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Database;

class FirebaseTicketSyncService
{
    /**
     * Mirror a ticket message to Realtime Database for live listeners.
     * Path: ticket_chats/{ticketId}/messages/{messageId}
     */
    public function syncMessage(TicketMessage $message): void
    {
        if (! filled(config('firebase.projects.app.database.url'))) {
            Log::debug('Firebase RTDB sync skipped: no database URL configured');
            return;
        }

        try {
            $database = app(Database::class);
        } catch (\Throwable $e) {
            Log::warning('Firebase RTDB: could not resolve Database binding', [
                'error' => $e->getMessage(),
            ]);
            return;
        }

        try {
            $message->loadMissing('sender');

            $database->getReference('ticket_chats/'.$message->ticket_id.'/messages/'.$message->id)
                ->set([
                    'body' => $message->body,
                    'is_admin' => (bool) $message->is_admin,
                    'sender_id' => $message->sender_id,
                    'sender_name' => (string) ($message->sender?->name ?? ''),
                    'created_at' => $message->created_at?->toIso8601String() ?? now()->toIso8601String(),
                ]);

            Log::info('Firebase RTDB: ticket message synced', [
                'ticket_id' => $message->ticket_id,
                'message_id' => $message->id,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Firebase RTDB ticket sync failed', [
                'ticket_id' => $message->ticket_id,
                'message_id' => $message->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

