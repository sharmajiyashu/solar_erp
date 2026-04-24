<?php

namespace App\Support;

class FirebaseWebPush
{
    /**
     * Browser can register for FCM (VAPID key recommended; add FIREBASE_WEB_VAPID_KEY from
     * Firebase Console → Project settings → Cloud Messaging → Web Push certificates).
     */
    public static function enabled(): bool
    {
        $w = config('services.firebase_web', []);

        return filled($w['api_key'] ?? null)
            && filled($w['app_id'] ?? null)
            && filled($w['messaging_sender_id'] ?? null);
    }
}
