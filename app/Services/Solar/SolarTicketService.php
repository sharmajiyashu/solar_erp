<?php

namespace App\Services\Solar;

use App\Events\TicketMessagePosted;
use App\Models\ServiceSlot;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Services\Firebase\FirebaseTicketSyncService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SolarTicketService
{
    public function __construct(
        private FcmDispatcher $fcm,
        private FirebaseTicketSyncService $firebaseTicketSync
    ) {}

    public function createTicket(User $user, ServiceSlot $slot, string $subject, string $messageBody): Ticket
    {
        if ((int) $slot->user_id !== (int) $user->id) {
            throw ValidationException::withMessages(['slot' => 'You cannot open a ticket for this slot.']);
        }

        return DB::transaction(function () use ($user, $slot, $subject, $messageBody) {
            $ticket = Ticket::create([
                'user_id' => $user->id,
                'service_slot_id' => $slot->id,
                'subject' => $subject,
                'status' => Ticket::STATUS_OPEN,
            ]);

            $message = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'sender_id' => $user->id,
                'is_admin' => false,
                'body' => $messageBody,
            ]);

            broadcast(new TicketMessagePosted($message));
            $this->firebaseTicketSync->syncMessage($message);

            Log::info('Ticket created', ['ticket_id' => $ticket->id, 'user_id' => $user->id]);

            // Notify all admins about the new ticket
            $admins = User::where('role', 'admin')->whereNotNull('fcm_token')->get();
            foreach ($admins as $admin) {
                $this->fcm->sendToUser(
                    $admin,
                    'New Ticket Created',
                    $user->name . ' has opened a new ticket: ' . $subject,
                    ['type' => 'new_ticket', 'ticket_id' => (string) $ticket->id]
                );
            }

            return $ticket->load('messages.sender');
        });
    }

    public function reply(User $sender, Ticket $ticket, string $body, bool $asAdmin): TicketMessage
    {
        if ($asAdmin) {
            if (! $sender->isAdminUser() || ! $sender->can('ticket_management')) {
                throw ValidationException::withMessages(['ticket' => 'Not allowed to reply to this ticket.']);
            }
        } else {
            if ((int) $ticket->user_id !== (int) $sender->id) {
                throw ValidationException::withMessages(['ticket' => 'Not allowed to reply to this ticket.']);
            }
        }

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => $sender->id,
            'is_admin' => $asAdmin,
            'body' => $body,
        ]);

        if ($asAdmin) {
            $ticket->update(['status' => Ticket::STATUS_IN_PROGRESS]);
        }

        broadcast(new TicketMessagePosted($message));
        $this->firebaseTicketSync->syncMessage($message);

        // FCM: admin replies → notify user
        $ticket->load('user');
        if ($asAdmin && $ticket->user && $ticket->user->fcm_token) {
            $snippet = strlen($body) > 100 ? substr($body, 0, 97) . '...' : $body;
            $this->fcm->sendToUser(
                $ticket->user,
                $sender->name . ' replied',
                $snippet,
                ['type' => 'ticket_reply', 'ticket_id' => (string) $ticket->id]
            );
        }

        // FCM: user replies → notify assigned admin OR all admins if unassigned
        if (! $asAdmin) {
            $snippet = strlen($body) > 100 ? substr($body, 0, 97) . '...' : $body;
            if ($ticket->assigned_admin_id) {
                $admin = User::find($ticket->assigned_admin_id);
                if ($admin && $admin->fcm_token) {
                    $this->fcm->sendToUser(
                        $admin,
                        $sender->name . ' (New Message)',
                        $snippet,
                        ['type' => 'ticket_reply', 'ticket_id' => (string) $ticket->id]
                    );
                }
            } else {
                // Not assigned yet? Notify all admins
                $admins = User::where('role', 'admin')->whereNotNull('fcm_token')->get();
                foreach ($admins as $admin) {
                    $this->fcm->sendToUser(
                        $admin,
                        $sender->name . ' (New Ticket Reply)',
                        $snippet,
                        ['type' => 'ticket_reply', 'ticket_id' => (string) $ticket->id]
                    );
                }
            }
        }

        return $message->load('sender');
    }

    public function updateStatus(User $admin, Ticket $ticket, string $status): Ticket
    {
        if (! $admin->isAdminUser() || ! $admin->can('ticket_management')) {
            throw ValidationException::withMessages(['ticket' => 'Forbidden']);
        }

        if (! in_array($status, [Ticket::STATUS_OPEN, Ticket::STATUS_IN_PROGRESS, Ticket::STATUS_CLOSED], true)) {
            throw ValidationException::withMessages(['status' => 'Invalid status']);
        }

        $ticket->update(['status' => $status, 'assigned_admin_id' => $admin->id]);

        Log::info('Ticket status updated', ['ticket_id' => $ticket->id, 'status' => $status, 'admin_id' => $admin->id]);

        // Notify user about status change
        $ticket->load('user');
        if ($ticket->user && $ticket->user->fcm_token) {
            $this->fcm->sendToUser(
                $ticket->user,
                'Ticket Status Updated',
                'Your ticket #' . $ticket->id . ' is now: ' . str_replace('_', ' ', $status),
                ['type' => 'status_update', 'ticket_id' => (string) $ticket->id, 'status' => $status]
            );
        }

        return $ticket->fresh();
    }
}
