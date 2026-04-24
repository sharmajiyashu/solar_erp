@extends('user.layouts.app')

@section('content')
<div class="user-ticket-workspace">
    <div class="workspace-header border-bottom bg-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('user.tickets.index') }}" class="btn btn-sm btn-light border-0 rounded-circle">
                <i class="bi bi-chevron-left"></i>
            </a>
            <div>
                <h6 class="mb-0 fw-bold">{{ $ticket->subject }}</h6>
                <div class="text-muted small">#{{ $ticket->id }} • {{ $ticket->slot?->service_date?->format('d M') }}</div>
            </div>
        </div>
        <span class="badge bg-{{ $ticket->status === 'closed' ? 'success' : ($ticket->status === 'in_progress' ? 'primary' : 'danger') }} rounded-pill px-3">
            {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
        </span>
    </div>

    <div class="workspace-body">
        <div class="chat-container-wrapper">
            <div class="chat-container-main">
                @include('components.firebase-ticket-chat', ['firebaseChat' => $firebaseChat, 'ticket' => $ticket, 'layout' => 'full'])
            </div>
        </div>
    </div>
</div>

<style>
    /* PREVENT ALL SYSTEM SCROLLING - LOCK TO APP VIEW */
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        height: 100% !important;
        width: 100% !important;
        overflow: hidden !important;
        position: fixed !important; /* Forces iPhone to stay put */
    }

    .main-content {
        padding: 0 !important;
        margin: 0 !important;
        height: 100vh !important;
        height: 100dvh !important;
        width: 100% !important;
    }

    .user-ticket-workspace {
        position: fixed !important;
        top: 0 !important;
        left: var(--sidebar-width, 280px) !important;
        right: 0 !important;
        bottom: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        background: #fff !important;
        z-index: 2000 !important;
        height: 100% !important;
    }

    .workspace-header {
        flex-shrink: 0 !important;
        background: #fff;
        padding: 10px 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .workspace-body {
        flex: 1 !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        background: #f8fafc;
        position: relative !important;
    }

    .chat-container-wrapper {
        flex: 1 !important;
        display: flex !important;
        justify-content: center !important;
        overflow: hidden !important;
        height: 100% !important;
    }

    .chat-container-main {
        width: 100%;
        max-width: 900px;
        height: 100% !important;
        background: #fff;
        display: flex !important;
        flex-direction: column !important;
        box-shadow: 0 0 40px rgba(0,0,0,0.03);
        overflow: hidden !important;
    }

    /* MOBILE OVERRIDES */
    @media (max-width: 991px) {
        .user-ticket-workspace {
            left: 0 !important;
            width: 100% !important;
        }
    }
</style>
@endsection
