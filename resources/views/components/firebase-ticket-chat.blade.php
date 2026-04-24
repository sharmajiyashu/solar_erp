{{-- Realtime ticket messages: Laravel saves to DB + RTDB; listeners for other party; AJAX send for instant UI. --}}
<style>
    #chat-wrapper {
        background: #fff;
        width: 100%;
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-sizing: border-box;
        font-family: 'Inter', -apple-system, sans-serif;
        overflow: hidden;
    }
    #chat-header {
        padding: 12px 20px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .status-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
    #chat-header h6 { margin: 0; font-weight: 600; color: #334155; font-size: 15px; }
    
    #chat-box {
        flex: 1;
        overflow-y: auto;
        padding: 30px;
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
    }
    .msg-bubble {
        max-width: 80%;
        padding: 12px 18px;
        margin-bottom: 15px;
        border-radius: 20px;
        font-size: 14px;
        line-height: 1.6;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        word-wrap: break-word;
    }
    .msg-user {
        background: #2563eb;
        color: #fff;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }
    .msg-admin {
        background: #fff;
        color: #1e293b;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        border: 1px solid #e2e8f0;
    }
    .msg-info {
        font-size: 10px;
        margin-top: 6px;
        opacity: 0.7;
        text-align: right;
    }
    .msg-admin .msg-info { text-align: left; }
    
    #chat-footer {
        flex-shrink: 0 !important;
        padding: 12px 20px !important;
        background: #fff !important;
        border-top: 1px solid #f1f5f9 !important;
        position: relative !important;
        z-index: 50 !important;
        padding-bottom: calc(12px + env(safe-area-inset-bottom)) !important;
    }
    #ticket-reply-form {
        display: flex !important;
        gap: 8px !important;
        align-items: center !important;
        background: #f8fafc !important;
        padding: 6px 6px 6px 16px !important;
        border-radius: 25px !important;
        border: 1px solid #e2e8f0 !important;
    }
    .input-group-custom {
        flex: 1 !important;
        display: flex !important;
        align-items: center !important;
    }
    #ticket-reply-form input {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        width: 100% !important;
        padding: 8px 0 !important;
        font-size: 16px !important; /* PREVENTS IPHONE AUTO-ZOOM */
        color: #0f172a !important;
    }
    #ticket-reply-form button {
        width: 36px !important;
        height: 36px !important;
        min-width: 36px !important;
        border-radius: 50% !important;
        background: #2563eb !important;
        color: #fff !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
    }

    /* MOBILE OPTIMIZATIONS */
    @media (max-width: 768px) {
        #chat-footer {
            padding: 8px 12px !important;
            padding-bottom: calc(8px + env(safe-area-inset-bottom)) !important;
        }
        #ticket-reply-form {
            padding: 4px 4px 4px 12px !important;
        }
        #ticket-reply-form input {
            font-size: 16px !important;
        }
    }
</style>

<div id="chat-wrapper">
    <div id="chat-header">
        <div class="status-dot"></div>
        <h6>Support: {{ $ticket->user?->name }}</h6>
        @if(empty($firebaseChat['enabled']))
            <span class="badge bg-light-warning text-warning ms-auto small">Offline</span>
        @endif
    </div>
    <div id="chat-box">
        @foreach($ticket->messages as $m)
            @php
                $isMe = ($firebaseChat['layout'] === 'admin') ? $m->is_admin : !$m->is_admin;
            @endphp
            <div class="msg-bubble {{ $isMe ? 'msg-user' : 'msg-admin' }}" data-message-id="{{ $m->id }}">
                <div class="msg-text">{!! nl2br(e($m->body)) !!}</div>
                <div class="msg-info">
                    <span>{{ $m->sender?->name }} • {{ $m->created_at?->format('H:i') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    @if($ticket->status !== 'closed')
    <div id="chat-footer">
        <form id="ticket-reply-form" method="post" action="{{ $firebaseChat['reply_url'] ?? '#' }}">
            @csrf
            <div class="input-group-custom">
                <input type="text" name="message" id="chat-input" placeholder="Type your message..." required autocomplete="off">
            </div>
            <button type="submit">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </form>
    </div>
    @endif
</div>

<script>
// Chat Scroll Logic and initialization
</script>

@if(!empty($firebaseChat['enabled']))
@push('scripts')
<script>
(function() {
    // ... Firebase initialization logic remains here ...
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
        const web = @json($firebaseChat['web'] ?? []);
        const cfg = {
            apiKey: web.api_key || '',
            authDomain: web.auth_domain || '',
            databaseURL: web.database_url || "{{ config('firebase.projects.app.database.url') }}",
            projectId: web.project_id || '',
            storageBucket: web.storage_bucket || '',
            messagingSenderId: web.messaging_sender_id || '',
            appId: web.app_id || ''
        };

        const ticketId = {{ $ticket->id }};
        const tokenUrl = @json($firebaseChat['token_url']);
        const layout = @json($firebaseChat['layout']);
        const box = document.getElementById('chat-box');
        const replyForm = document.getElementById('ticket-reply-form');
        
        if (!box || !cfg.apiKey || !cfg.databaseURL) {
            console.error('Chat Init Error: Missing Firebase Config Keys', { hasBox: !!box, hasKey: !!cfg.apiKey, hasDb: !!cfg.databaseURL });
            return;
        }
        const renderedIds = new Set();
        function updateRenderedIds() {
            box.querySelectorAll('[data-message-id]').forEach(function (el) {
                renderedIds.add(String(el.getAttribute('data-message-id')));
            });
        }
        updateRenderedIds();
        box.scrollTop = box.scrollHeight;

        function appendMessage(id, v) {
            if (renderedIds.has(String(id))) return;
            renderedIds.add(String(id));
            
            const isAdmin = !!v.is_admin;
            const isMe = (layout === 'admin') ? isAdmin : !isAdmin;
            const bubbleClass = isMe ? 'msg-user' : 'msg-admin';
            
            const div = document.createElement('div');
            div.className = 'msg-bubble ' + bubbleClass;
            div.setAttribute('data-message-id', id);
            
            let dateStr = '';
            if (v.created_at) {
                try {
                    const date = new Date(v.created_at);
                    dateStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                } catch(e) { dateStr = ''; }
            }

            div.innerHTML = `
                <div class="msg-text">${v.body || ''}</div>
                <div class="msg-info">
                    <span>${v.sender_name || 'System'} • ${dateStr}</span>
                </div>
            `;
            
            box.appendChild(div);
            box.scrollTop = box.scrollHeight;
        }

        if (!firebase.apps || !firebase.apps.length) {
            firebase.initializeApp(cfg);
        }

        function wireRealtime() {
            const currentUserId = "{{ auth()->id() }}";
            const database = firebase.database();
            const messagesRef = database.ref('ticket_chats/' + ticketId + '/messages');
            
            // Notification Sound
            const pingSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3');
            let firstLoad = true;

            messagesRef.on('child_added', function (snap) {
                const msg = snap.val();
                appendMessage(snap.key, msg);
                
                // Play sound for new messages (not during initial load and not from self)
                if (!firstLoad && msg.sender_id != currentUserId) {
                    pingSound.play().catch(e => console.log('Audio play blocked by browser:', e));
                }

                // Force scroll to bottom on new message
                setTimeout(() => { box.scrollTop = box.scrollHeight; }, 100);
            });

            // Handle keyboard focus scroll
            const input = document.getElementById('chat-input');
            if (input) {
                input.addEventListener('focus', () => {
                    setTimeout(() => { 
                        box.scrollTop = box.scrollHeight;
                        window.scrollTo(0, 0); // Prevent page level scroll on iPhone
                    }, 300);
                });
            }

            // Mark initial load finished after a short delay
            setTimeout(() => { 
                firstLoad = false; 
                box.scrollTop = box.scrollHeight;
            }, 2000);
        }

        fetch(tokenUrl, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.token) return firebase.auth().signInWithCustomToken(data.token);
        })
        .then(() => wireRealtime())
        .catch(e => console.error('Chat Init Error:', e));

        if (replyForm) {
            replyForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const input = document.getElementById('chat-input');
                if (!input || !input.value.trim()) return;
                
                const fd = new FormData(replyForm);
                fetch(replyForm.action, {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                })
                .then(r => r.json())
                .then(data => {
                    if (data.ok) {
                        appendMessage(String(data.message.id), data.message);
                        input.value = '';
                    }
                })
                .catch(() => replyForm.submit());
            });
        }
    }
})();
</script>
@endpush
@endif
