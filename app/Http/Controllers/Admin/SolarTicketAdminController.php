<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\Solar\SolarTicketService;
use App\Support\FirebaseTicketChat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class SolarTicketAdminController extends Controller
{
    public function __construct(
        private SolarTicketService $tickets
    ) {}

    public function index(Request $request): View
    {
        $tickets = Ticket::query()
            ->with(['user', 'slot'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('updated_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.solar.tickets_index', compact('tickets'));
    }

    public function show(Request $request, Ticket $ticket): View
    {
        $ticket->load([
            'messages' => fn ($q) => $q->orderBy('created_at'),
            'messages.sender',
            'user',
            'slot',
        ]);

        $recentTickets = Ticket::query()
            ->with(['user', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->orderByDesc('updated_at')
            ->limit(15)
            ->get();

        $firebaseChat = FirebaseTicketChat::forAdminTicket($request, $ticket);

        return view('admin.solar.tickets_show', compact('ticket', 'firebaseChat', 'recentTickets'));
    }

    public function reply(Request $request, Ticket $ticket): JsonResponse|RedirectResponse
    {
        try {
            $request->validate(['message' => 'required|string|max:5000']);
            $message = $this->tickets->reply($request->user(), $ticket, $request->message, true);
            $message->loadMissing('sender');
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'errors' => $e->errors()], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'is_admin' => (bool) $message->is_admin,
                    'sender_name' => (string) ($message->sender?->name ?? ''),
                    'created_at' => $message->created_at?->toIso8601String() ?? now()->toIso8601String(),
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Reply sent.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status' => 'required|in:open,in_progress,closed']);

        try {
            $this->tickets->updateStatus($request->user(), $ticket, $request->status);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return redirect()->back()->with('success', 'Ticket status updated.');
    }
}
