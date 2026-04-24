<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Symfony\Component\HttpFoundation\Response;

class FirebaseCustomTokenController extends Controller
{
    public function userTicketToken(Request $request, Ticket $ticket): JsonResponse
    {
        abort_unless((int) $ticket->user_id === (int) $request->user()->id, Response::HTTP_FORBIDDEN);

        return $this->mint($request, 'laravel_user_'.$request->user()->id, [
            'ticket_id' => (string) $ticket->id,
            'role' => 'customer',
        ]);
    }

    public function adminTicketToken(Request $request, Ticket $ticket): JsonResponse
    {
        abort_unless($request->user()->isAdminUser() && $request->user()->can('ticket_management'), Response::HTTP_FORBIDDEN);

        return $this->mint($request, 'laravel_admin_'.$request->user()->id, [
            'ticket_id' => (string) $ticket->id,
            'is_ticket_admin' => true,
        ]);
    }

    private function mint(Request $request, string $uid, array $claims): JsonResponse
    {
        try {
            /** @var FirebaseAuth $auth */
            $auth = app(FirebaseAuth::class);
            $token = $auth->createCustomToken($uid, $claims);

            Log::debug('Firebase custom token created', ['uid' => $uid, 'claims' => $claims]);
        } catch (\Throwable $e) {
            Log::error('Firebase custom token creation failed', [
                'uid' => $uid,
                'claims' => $claims,
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            report($e);

            return response()->json(['error' => 'Could not create Firebase token. Check FIREBASE_CREDENTIALS.'], 503);
        }

        return response()->json(['token' => $token->toString()]);
    }
}

