@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-fluid p-0">
        <div class="content-body" style="height: calc(100vh - 100px); min-height: 400px;">
            <div class="d-flex bg-white overflow-hidden h-100 shadow-sm rounded-3">
                
                <!-- MAIN CHAT AREA -->
                <div class="flex-grow-1 d-flex flex-column bg-white border-end chat-column h-100">
                    @include('components.firebase-ticket-chat', ['firebaseChat' => $firebaseChat, 'ticket' => $ticket, 'layout' => 'full'])
                </div>

                <!-- TICKET DETAILS SIDEBAR (Hidden on small screens) -->
                <div class="info-sidebar d-none d-lg-flex flex-column bg-light-subtle h-100" style="width: 320px; min-width: 320px;">
                    <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Ticket Info</h5>
                        <a href="{{ route('admin.solar.tickets.index') }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                    
                    <div class="p-4 flex-grow-1 overflow-auto custom-scrollbar">
                        <div class="mb-4 text-center">
                            <h3 class="fw-black mb-1">#{{ $ticket->id }}</h3>
                            <p class="text-muted small mb-3">{{ $ticket->subject }}</p>
                            <span class="badge bg-{{ $ticket->status === 'closed' ? 'success' : ($ticket->status === 'in_progress' ? 'primary' : 'danger') }} rounded-pill px-3">
                                {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </div>

                        <form method="post" action="{{ route('admin.solar.tickets.status', $ticket) }}" class="mb-4">
                            @csrf
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2">Change Status</label>
                            <div class="input-group input-group-sm">
                                <select name="status" class="form-select border-primary-subtle">
                                    <option value="open" @selected($ticket->status==='open')>Open</option>
                                    <option value="in_progress" @selected($ticket->status==='in_progress')>In Progress</option>
                                    <option value="closed" @selected($ticket->status==='closed')>Closed</option>
                                </select>
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </form>

                        <div class="p-3 bg-white rounded-4 border shadow-sm mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar bg-primary-subtle rounded-circle me-2 p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-person text-primary"></i>
                                </div>
                                <div class="fw-bold small text-truncate">{{ $ticket->user?->name }}</div>
                            </div>
                            <div class="text-muted small text-truncate ps-4">{{ $ticket->user?->email }}</div>
                        </div>

                        <div class="p-3 bg-white rounded-4 border shadow-sm">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Service Visit</span>
                                <span class="fw-bold small">{{ $ticket->slot?->service_date?->format('d M, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Slot ID</span>
                                <span class="fw-bold text-primary small">#{{ $ticket->service_slot_id }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-top bg-white">
                        <a href="{{ route('admin.solar.tickets.index') }}" class="btn btn-outline-secondary w-100 rounded-pill btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Dashboard
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* PREVENT DOUBLE SCROLL BUT ALLOW SYSTEM HEADER */
    .app-content.content {
        overflow: hidden !important;
        height: 100vh;
    }
    
    .content-body {
        height: calc(100vh - 110px) !important;
        padding: 0 !important;
    }

    @media (max-width: 991px) {
        .content-body {
            height: 100dvh !important;
        }
        .app-content.content {
            padding: 0 !important;
            margin: 0 !important;
        }
    }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection
