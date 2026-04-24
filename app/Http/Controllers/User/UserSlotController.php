<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solar\StoreTechnicianReviewRequest;
use App\Http\Requests\Solar\StoreTicketRequest;
use App\Models\ServiceSlot;
use App\Models\Ticket;
use App\Services\Solar\SolarTicketService;
use App\Services\Solar\TechnicianReviewService;
use App\Support\FirebaseTicketChat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class UserSlotController extends Controller
{
    public function __construct(
        private SolarTicketService $tickets,
        private TechnicianReviewService $technicianReviews
    ) {
        $this->middleware('auth');
    }

    public function slots(Request $request): View
    {
        $slots = ServiceSlot::query()
            ->where('user_id', $request->user()->id)
            ->with(['assignedAdmin:id,name,email,mobile', 'subscription.package', 'technicianReview'])
            ->orderByDesc('service_date')
            ->paginate(20);

        return view('user.slots', compact('slots'));
    }

    public function storeTechnicianReview(StoreTechnicianReviewRequest $request, ServiceSlot $slot)
    {
        abort_unless((int) $slot->user_id === (int) $request->user()->id, 403);

        try {
            $this->technicianReviews->storeFromCustomer(
                $request->user(),
                $slot,
                (int) $request->rating,
                $request->comment
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('user.slots')->with('success', 'Thank you for rating your technician.');
    }

    public function tickets(Request $request): View
    {
        $tickets = Ticket::query()
            ->where('user_id', $request->user()->id)
            ->with(['slot'])
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('user.tickets_index', compact('tickets'));
    }

    public function ticketShow(Request $request, Ticket $ticket): View
    {
        abort_unless((int) $ticket->user_id === (int) $request->user()->id, 403);
        $ticket->load([
            'messages' => fn ($q) => $q->orderBy('created_at'),
            'messages.sender',
            'slot',
        ]);

        $firebaseChat = FirebaseTicketChat::forUserTicket($request, $ticket);

        return view('user.tickets_show', compact('ticket', 'firebaseChat'));
    }

    public function storeTicket(StoreTicketRequest $request)
    {
        $slot = ServiceSlot::findOrFail($request->service_slot_id);

        try {
            $ticket = $this->tickets->createTicket(
                $request->user(),
                $slot,
                $request->subject,
                $request->message
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('user.tickets.show', $ticket)->with('success', 'Ticket created.');
    }

    public function replyTicket(Request $request, Ticket $ticket): JsonResponse|RedirectResponse
    {
        abort_unless((int) $ticket->user_id === (int) $request->user()->id, 403);

        try {
            $request->validate(['message' => 'required|string|max:5000']);
            $message = $this->tickets->reply($request->user(), $ticket, $request->message, false);
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

        return redirect()->back()->with('success', 'Message sent.');
    }

    public function saveFcmToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fcm_token' => 'nullable|string|max:4096',
        ]);

        $token = isset($validated['fcm_token']) ? trim((string) $validated['fcm_token']) : '';

        $request->user()->forceFill([
            'fcm_token' => $token !== '' ? $token : null,
        ])->save();

        return response()->json([
            'status' => true,
            'saved' => $token !== '',
        ]);
    }
}
