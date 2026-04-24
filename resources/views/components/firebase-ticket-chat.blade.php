{{-- Realtime ticket messages: Laravel saves to DB + RTDB; listeners for other party; AJAX send for instant UI. --}}
@if(!empty($firebaseChat['enabled']))
@push('scripts')
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js"></script>
<script>
(function () {
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
        const ref = firebase.database().ref('ticket_chats/' + ticketId + '/messages');
        ref.on('child_added', function (snap) {
            appendMessage(snap.key, snap.val() || {});
        }, function (err) {
            console.warn('Firebase ticket chat read denied or failed', err && err.code ? err.code : err);
        });
    }

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
            if (!data.token) throw new Error(data.error || 'No token');
            return firebase.auth().signInWithCustomToken(data.token);
        })
        .then(function () {
            wireRealtime();
        })
        .catch(function (e) {
            console.warn('Firebase chat init failed', e);
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
})();
</script>
@endpush
@endif
