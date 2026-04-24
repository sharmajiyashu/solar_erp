<?php

namespace App\Services\Solar;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmDispatcher
{
    public function sendToUser(?User $user, string $title, string $body, array $data = []): void
    {
        if (! $user || ! $user->fcm_token) {
            Log::debug('FCM skipped: user has no FCM token', ['user_id' => $user?->id]);
            return;
        }

        try {
            /** @var Messaging $messaging */
            $messaging = app(Messaging::class);
            $message = CloudMessage::new()
                ->toToken($user->fcm_token)
                ->withNotification(Notification::create($title, $body))
                ->withData(array_map('strval', $data))
                ->withAndroidConfig(['notification' => ['sound' => 'default']])
                ->withApnsConfig(['payload' => ['aps' => ['sound' => 'default']]]);
            $messaging->send($message);

            Log::info('FCM sent successfully via Kreait SDK', ['user_id' => $user->id, 'title' => $title]);
            return;
        } catch (\Throwable $e) {
            Log::warning('FCM via Kreait failed, trying HTTP v1 fallback', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        $credentialsPath = config('services.fcm.credentials');
        $projectId = config('services.fcm.project_id');

        if (! $projectId && $credentialsPath && is_readable((string) $credentialsPath)) {
            $decoded = json_decode((string) file_get_contents((string) $credentialsPath), true);
            $projectId = is_array($decoded) ? ($decoded['project_id'] ?? null) : null;
        }

        if (! $projectId || ! $credentialsPath || ! is_readable((string) $credentialsPath)) {
            Log::warning('FCM HTTP fallback skipped: missing credentials or project_id', [
                'has_project_id' => (bool) $projectId,
                'has_credentials' => (bool) $credentialsPath,
                'credentials_readable' => $credentialsPath ? is_readable((string) $credentialsPath) : false,
            ]);

            return;
        }

        $accessToken = $this->accessToken((string) $credentialsPath);
        if (! $accessToken) {
            return;
        }

        $url = 'https://fcm.googleapis.com/v1/projects/'.$projectId.'/messages:send';
        $payload = [
            'message' => [
                'token' => $user->fcm_token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'android' => ['notification' => ['sound' => 'default']],
                'apns' => ['payload' => ['aps' => ['sound' => 'default']]],
                'data' => array_map('strval', $data),
            ],
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if (! $response->successful()) {
            Log::warning('FCM HTTP v1 send failed', [
                'user_id' => $user->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } else {
            Log::info('FCM sent successfully via HTTP v1', ['user_id' => $user->id, 'title' => $title]);
        }
    }

    private function accessToken(string $credentialsPath): ?string
    {
        $json = json_decode((string) file_get_contents($credentialsPath), true);
        if (! is_array($json) || empty($json['private_key']) || empty($json['client_email'])) {
            return null;
        }

        $now = time();
        $jwtHeader = $this->b64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $jwtClaim = $this->b64url(json_encode([
            'iss' => $json['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ]));

        $signingInput = $jwtHeader.'.'.$jwtClaim;
        $signature = '';
        $ok = openssl_sign($signingInput, $signature, $json['private_key'], OPENSSL_ALGO_SHA256);
        if (! $ok) {
            return null;
        }
        $jwt = $signingInput.'.'.$this->b64url($signature);

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if (! $tokenResponse->successful()) {
            Log::warning('FCM OAuth token failed', ['body' => $tokenResponse->body()]);

            return null;
        }

        return $tokenResponse->json('access_token');
    }

    private function b64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
