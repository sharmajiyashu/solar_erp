@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-3">
        <a href="{{ route('user.tickets.index') }}" class="text-decoration-none small fw-bold text-primary">← Tickets</a>
    </div>
    <h4 class="fw-black">{{ $ticket->subject }}</h4>
    <p class="text-muted small">Status: <span class="badge bg-secondary">{{ $ticket->status }}</span>
        @if(!empty($firebaseChat['enabled']))
            <span class="badge bg-success ms-1">Live chat (Firebase)</span>
        @endif
    </p>
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body" id="chat-box" style="max-height: 400px; overflow-y: auto;">
            @foreach($ticket->messages as $m)
            <div class="mb-3 {{ $m->is_admin ? '' : 'text-end' }}" data-message-id="{{ $m->id }}">
                <div class="d-inline-block text-start p-3 rounded-4 {{ $m->is_admin ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 88%;">
                    <div class="small opacity-75">{{ $m->sender?->name }} · {{ $m->created_at?->format('M d H:i') }}</div>
                    <div>{{ $m->body }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <form id="ticket-reply-form" method="post" action="{{ route('user.tickets.reply', $ticket) }}" class="d-flex gap-2">
        @csrf
        <input type="text" name="message" class="form-control rounded-pill" placeholder="Write a message…" required maxlength="5000">
        <button class="btn btn-primary rounded-pill px-4" type="submit">Send</button>
    </form>
</div>
@include('components.firebase-ticket-chat', ['firebaseChat' => $firebaseChat, 'ticket' => $ticket])
@endsection
