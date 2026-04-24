<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solar\StoreTicketRequest;
use App\Http\Requests\Solar\TicketReplyRequest;
use App\Models\ServiceSlot;
use App\Models\Ticket;
use App\Services\Solar\SolarTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketsApiController extends Controller
{
    public function __construct(
        private SolarTicketService $tickets
    ) {
        $this->middleware('auth');
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $slot = ServiceSlot::findOrFail($request->service_slot_id);

        try {
            $ticket = $this->tickets->createTicket(
                Auth::user(),
                $slot,
                $request->subject,
                $request->message
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        return response()->json(['status' => true, 'data' => $ticket], 201);
    }

    public function reply(TicketReplyRequest $request): JsonResponse
    {
        $ticket = Ticket::findOrFail($request->ticket_id);
        $user = Auth::user();
        $asAdmin = $user->isAdminUser() && $user->can('ticket_management');

        try {
            $message = $this->tickets->reply($user, $ticket, $request->message, $asAdmin);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        return response()->json(['status' => true, 'data' => $message]);
    }
}
