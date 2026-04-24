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
            Log::info('FCM: dispatching ticket reply notification to user', [
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user->id,
            ]);
            $this->fcm->sendToUser(
                $ticket->user,
                'Ticket update',
                'You have a new reply on: '.$ticket->subject,
                ['type' => 'ticket_reply', 'ticket_id' => (string) $ticket->id]
            );
        }

        // FCM: user replies → notify assigned admin
        if (! $asAdmin && $ticket->assigned_admin_id) {
            $admin = User::find($ticket->assigned_admin_id);
            if ($admin && $admin->fcm_token) {
                Log::info('FCM: dispatching ticket reply notification to admin', [
                    'ticket_id' => $ticket->id,
                    'admin_id' => $admin->id,
                ]);
                $this->fcm->sendToUser(
                    $admin,
                    'New ticket reply',
                    $sender->name.' replied on: '.$ticket->subject,
                    ['type' => 'ticket_reply', 'ticket_id' => (string) $ticket->id]
                );
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

        return $ticket->fresh();
    }
}

