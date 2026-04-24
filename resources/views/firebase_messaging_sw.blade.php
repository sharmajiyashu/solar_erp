{{-- Served as /firebase-messaging-sw.js — keep in sync with Firebase JS version used elsewhere. --}}
@php
    $w = config('services.firebase_web', []);
    $firebaseApp = array_filter([
        'apiKey' => $w['api_key'] ?? null,
        'authDomain' => $w['auth_domain'] ?? null,
        'projectId' => $w['project_id'] ?? null,
        'storageBucket' => $w['storage_bucket'] ?? null,
        'messagingSenderId' => $w['messaging_sender_id'] ?? null,
        'appId' => $w['app_id'] ?? null,
    ]);
@endphp
@if(empty($firebaseApp['appId']))
// Configure FIREBASE_WEB_* in .env to enable background FCM.
@else
importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js');

firebase.initializeApp(@json($firebaseApp));

const messaging = firebase.messaging();
messaging.onBackgroundMessage(function (payload) {
    const title = (payload.notification && payload.notification.title) || @json(config('app.name', 'Notification'));
    const options = {
        body: (payload.notification && payload.notification.body) || '',
        data: payload.data || {},
    };
    return self.registration.showNotification(title, options);
});
@endif
