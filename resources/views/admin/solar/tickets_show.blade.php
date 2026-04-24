@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row mb-2">
            <div class="col-12">
                <h2 class="content-header-title">Ticket #{{ $ticket->id }}</h2>
                <p class="text-muted mb-0">{{ $ticket->subject }} — {{ $ticket->user?->name }}
                    @if(!empty($firebaseChat['enabled']))
                        <span class="badge bg-success ms-1">Live chat (Firebase)</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-2">
                        <div class="card-body" id="chat-box" style="max-height: 420px; overflow-y: auto;">
                            @foreach($ticket->messages as $m)
                            <div class="mb-3 {{ $m->is_admin ? 'text-end' : '' }}" data-message-id="{{ $m->id }}">
                                <div class="d-inline-block text-start p-2 rounded {{ $m->is_admin ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 85%;">
                                    <div class="small opacity-75">{{ $m->sender?->name }} · {{ $m->created_at?->format('M d H:i') }}</div>
                                    <div>{{ $m->body }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <form id="ticket-reply-form" method="post" action="{{ route('admin.solar.tickets.reply', $ticket) }}">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Type a reply…" required maxlength="5000">
                            <button class="btn btn-primary" type="submit">Send</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.solar.tickets.status', $ticket) }}">
                                @csrf
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select mb-2">
                                    <option value="open" @selected($ticket->status==='open')>Open</option>
                                    <option value="in_progress" @selected($ticket->status==='in_progress')>In progress</option>
                                    <option value="closed" @selected($ticket->status==='closed')>Closed</option>
                                </select>
                                <button class="btn btn-outline-primary w-100">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('components.firebase-ticket-chat', ['firebaseChat' => $firebaseChat, 'ticket' => $ticket])
@endsection
