{{-- Registers web FCM token → POST user.fcm_token (ticket admin replies trigger FcmDispatcher). --}}
@if(\App\Support\FirebaseWebPush::enabled())
@push('scripts')
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js"></script>
<script>
(function () {
    const cfg = @json(config('services.firebase_web'));
    const vapid = cfg.vapid_key ? String(cfg.vapid_key).trim() : '';
    const saveUrl = @json(route('user.fcm_token'));
    const swUrl = @json(route('firebase.messaging.sw'));
    const csrf = document.querySelector('meta[name="csrf-token"]');
    if (!cfg.api_key || !cfg.app_id || !cfg.messaging_sender_id || !csrf || !csrf.content) return;
    if (!('Notification' in window) || !('serviceWorker' in navigator)) return;

    function ensureApp() {
        if (firebase.apps && firebase.apps.length) return firebase.app();
        return firebase.initializeApp({
            apiKey: cfg.api_key,
            authDomain: cfg.auth_domain || undefined,
            databaseURL: cfg.database_url || undefined,
            projectId: cfg.project_id || undefined,
            storageBucket: cfg.storage_bucket || undefined,
            messagingSenderId: cfg.messaging_sender_id,
            appId: cfg.app_id,
        });
    }

    function postToken(token) {
        if (!token) return;
        var fd = new FormData();
        fd.append('_token', csrf.content);
        fd.append('fcm_token', token);
        fetch(saveUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf.content,
            },
            body: fd,
        })
            .then(function (r) {
                return r.text().then(function (t) {
                    var data = {};
                    try {
                        data = t ? JSON.parse(t) : {};
                    } catch (e) {
                        data = { raw: t };
                    }
                    return { ok: r.ok, status: r.status, data: data };
                });
            })
            .then(function (res) {
                if (!res.ok) {
                    console.warn('FCM token save failed', res.status, res.data);
                }
            })
            .catch(function (e) {
                console.warn('FCM token save request error', e);
            });
    }

    ensureApp();
    const messaging = firebase.messaging();

    function handleToken(token) {
        if (!token) return;
        const loginInput = document.getElementById('login_fcm_token');
        if (loginInput) loginInput.value = token;
        postToken(token);
    }

    Notification.requestPermission().then(function (perm) {
        if (perm !== 'granted') throw new Error('Permission not granted');
        return navigator.serviceWorker.register(swUrl, { scope: '/' });
    }).then(function (reg) {
        return navigator.serviceWorker.ready;
    }).then(function (reg) {
        var opts = { serviceWorkerRegistration: reg };
        if (vapid) opts.vapidKey = vapid;
        return messaging.getToken(opts);
    }).then(function (token) {
        handleToken(token);
    }).catch(function (e) {
        if (e.name === 'AbortError') {
            console.warn('FCM: Push service unavailable (AbortError). This often happens if notifications are blocked or the network is restricted.');
        } else {
            console.warn('FCM registration skipped:', e.message);
        }
    });
})();
</script>
@endpush
@endif
