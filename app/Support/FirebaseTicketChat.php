<?php

namespace App\Support;

use App\Models\Ticket;
use Illuminate\Http\Request;

class FirebaseTicketChat
{
    public static function enabled(): bool
    {
        $web = config('services.firebase_web', []);

        return filled($web['api_key'] ?? null)
            && filled($web['app_id'] ?? null)
            && filled(config('firebase.projects.app.database.url'))
            && filled(config('firebase.projects.app.credentials'));
    }

    /**
     * @return array{enabled: bool, web: array<string, mixed>, token_url: string, layout: 'user'|'admin'}
     */
    public static function forUserTicket(Request $request, Ticket $ticket): array
    {
        return [
            'enabled' => self::enabled(),
            'web' => config('services.firebase_web', []),
            'token_url' => route('user.tickets.firebase-token', $ticket),
            'layout' => 'user',
        ];
    }

    /**
     * @return array{enabled: bool, web: array<string, mixed>, token_url: string, layout: 'user'|'admin'}
     */
    public static function forAdminTicket(Request $request, Ticket $ticket): array
    {
        return [
            'enabled' => self::enabled(),
            'web' => config('services.firebase_web', []),
            'token_url' => route('admin.solar.tickets.firebase-token', $ticket),
            'layout' => 'admin',
        ];
    }
}
