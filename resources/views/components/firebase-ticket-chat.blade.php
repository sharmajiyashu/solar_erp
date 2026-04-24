{{-- Realtime ticket messages: Laravel saves to DB + RTDB; listeners for other party; AJAX send for instant UI. --}}
@if(!empty($firebaseChat['enabled']))
@push('scripts')
<script>
(function() {
    if (!window.firebaseLoadPromise) {
        window.firebaseLoadPromise = new Promise((resolve) => {
            function loadScript(url, check, callback) {
                if (check()) return callback();
                var s = document.createElement('script');
                s.src = url;
                s.onload = callback;
                document.head.appendChild(s);
            }
            loadScript("https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js", 
                () => typeof firebase !== 'undefined', 
                function() {
                    loadScript("https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js", 
                        () => typeof firebase.auth !== 'undefined', 
                        function() {
                            loadScript("https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js", 
                                () => typeof firebase.database !== 'undefined', 
                                function() {
                                    loadScript("https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js",
                                        () => typeof firebase.messaging !== 'undefined',
                                        resolve
                                    );
                                }
                            );
                        }
                    );
                }
            );
        });
    }

    window.firebaseLoadPromise.then(initChat);

    function initChat() {
        // The rest of your script moves into this function

    const cfg = @json($firebaseChat['web']);
    const ticketId = {{ $ticket->id }};
    const tokenUrl = @json($firebaseChat['token_url']);
    const layout = @json($firebaseChat['layout']);
    const box = document.getElementById('chat-box');
    const replyForm = document.getElementById('ticket-reply-form');
    if (!box || !cfg.api_key || !cfg.database_url) return;

    const renderedIds = new Set();
    box.querySelectorAll('[data-message-id]').forEach(function (el) {
        renderedIds.add(String(el.getAttribute('data-message-id')));
    });

    function appendMessage(id, v) {
        if (renderedIds.has(String(id))) return;
        renderedIds.add(String(id));
        const isAdmin = !!v.is_admin;
        let align, bubble;
        if (layout === 'user') {
            align = isAdmin ? '' : 'text-end';
            bubble = isAdmin ? 'bg-primary text-white' : 'bg-light';
        } else {
            align = isAdmin ? 'text-end' : '';
            bubble = isAdmin ? 'bg-primary text-white' : 'bg-light';
        }
        const wrap = document.createElement('div');
        wrap.className = 'mb-3 ' + align;
        wrap.setAttribute('data-message-id', id);
        const inner = document.createElement('div');
        inner.className = 'd-inline-block text-start ' + (layout === 'admin' ? 'p-2 rounded ' : 'p-3 rounded-4 ') + bubble;
        inner.style.maxWidth = layout === 'admin' ? '85%' : '88%';
        const meta = document.createElement('div');
        meta.className = 'small opacity-75';
        meta.textContent = (v.sender_name || '') + ' · ' + (v.created_at ? String(v.created_at).slice(0, 16).replace('T', ' ') : '');
        const body = document.createElement('div');
        body.textContent = v.body || '';
        inner.appendChild(meta);
        inner.appendChild(body);
        wrap.appendChild(inner);
        box.appendChild(wrap);
        box.scrollTop = box.scrollHeight;
    }

    if (!firebase.apps || !firebase.apps.length) {
        firebase.initializeApp({
            apiKey: cfg.api_key,
            authDomain: cfg.auth_domain,
            databaseURL: cfg.database_url,
            projectId: cfg.project_id,
            storageBucket: cfg.storage_bucket,
            messagingSenderId: cfg.messaging_sender_id,
            appId: cfg.app_id,
        });
    }

    function wireRealtime() {
        console.log('FCM Chat: Connecting to database for ticket ' + ticketId);
        const ref = firebase.database().ref('ticket_chats/' + ticketId + '/messages');
        ref.on('child_added', function (snap) {
            const val = snap.val();
            console.log('FCM Chat: New message received', snap.key, val);
            appendMessage(snap.key, val || {});
        }, function (err) {
            console.error('FCM Chat: Database read failed', err);
        });
    }

    console.log('FCM Chat: Fetching token from ' + tokenUrl);
    fetch(tokenUrl, {
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    })
        .then(function (r) {
            return r.json().then(function (data) {
                if (!r.ok) throw new Error((data && data.error) ? data.error : ('HTTP ' + r.status));
                return data;
            });
        })
        .then(function (data) {
            console.log('FCM Chat: Token received, signing in...');
            if (!data.token) throw new Error(data.error || 'No token');
            return firebase.auth().signInWithCustomToken(data.token);
        })
        .then(function (user) {
            console.log('FCM Chat: Signed in successfully as ' + user.user.uid);
            wireRealtime();
        })
        .catch(function (e) {
            console.error('FCM Chat: Initialization failed', e);
        });

    if (replyForm) {
        replyForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const input = replyForm.querySelector('[name="message"]');
            if (!input || !String(input.value || '').trim()) return;
            const fd = new FormData(replyForm);
            fetch(replyForm.action, {
                method: 'POST',
                body: fd,
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then(function (r) {
                    return r.json().then(function (data) {
                        if (!r.ok) throw { data: data, status: r.status };
                        return data;
                    });
                })
                .then(function (data) {
                    if (!data.ok || !data.message) throw new Error('Bad response');
                    appendMessage(String(data.message.id), {
                        body: data.message.body,
                        is_admin: data.message.is_admin,
                        sender_name: data.message.sender_name,
                        created_at: data.message.created_at,
                    });
                    input.value = '';
                })
                .catch(function () {
                    replyForm.submit();
                });
        });
    }
    }
})();
</script>
@endpush
@endif
