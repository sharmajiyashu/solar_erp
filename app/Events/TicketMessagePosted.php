<?php

namespace App\Events;

use App\Models\TicketMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketMessagePosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public TicketMessage $message)
    {
        $this->message->loadMissing('sender');
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('ticket.'.$this->message->ticket_id)];
    }

    public function broadcastAs(): string
    {
        return 'ticket.message';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'ticket_id' => $this->message->ticket_id,
            'body' => $this->message->body,
            'is_admin' => $this->message->is_admin,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender?->name,
            'created_at' => $this->message->created_at?->toIso8601String(),
        ];
    }
}
