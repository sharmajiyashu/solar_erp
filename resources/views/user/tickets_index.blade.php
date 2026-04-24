@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-4 px-0 px-md-3">
    <!-- Clean Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-12 text-center text-md-start">
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb mb-0 small fw-bold text-uppercase" style="letter-spacing: 1px;">
                    <li class="breadcrumb-item text-primary"><a href="{{ route('user.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Support</li>
                </ol>
            </nav>
            <h2 class="fw-black text-dark mb-0">Customer Support</h2>
            <p class="text-muted mb-0 small fw-medium">Track and manage your inquiries and technical support requests.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-black text-dark mb-0">Active Conversations</h6>
            <a href="{{ route('user.slots') }}" class="btn btn-light btn-sm rounded-pill fw-bold text-primary px-3 border" style="font-size: 0.7rem;">
                <i class="fas fa-plus me-1"></i> New Ticket (From Slots)
            </a>
        </div>
        
        <div class="list-group list-group-flush">
            @forelse($tickets as $t)
            <a href="{{ route('user.tickets.show', $t) }}" class="list-group-item list-group-item-action border-0 py-3 px-4 mx-0 transition-all hover-light border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-start">
                        <div class="icon-box p-2 rounded-3 bg-light me-3 text-primary">
                            <i class="fas fa-ticket-alt fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $t->subject }}</h6>
                            <div class="d-flex align-items-center gap-3">
                                <small class="text-muted small">
                                    <i class="fas fa-calendar-check me-1 opacity-50"></i> Visit: {{ $t->slot?->service_date?->format('d M Y') ?? 'General' }}
                                </small>
                                <small class="text-muted small">
                                    <i class="fas fa-clock me-1 opacity-50"></i> {{ $t->created_at?->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    @php
                        $statusMeta = match($t->status) {
                            'open' => ['class' => 'badge-primary bg-blue-soft', 'label' => 'OPEN'],
                            'in_progress' => ['class' => 'badge-primary', 'label' => 'IN PROGRESS'],
                            'closed' => ['class' => 'badge-secondary', 'label' => 'CLOSED'],
                            default => ['class' => 'badge-secondary', 'label' => strtoupper($t->status)]
                        };
                    @endphp
                    <span class="badge {{ $statusMeta['class'] }} rounded-pill px-3 py-2 fw-black border border-opacity-10" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                        {{ $statusMeta['label'] }}
                    </span>
                </div>
            </a>
            @empty
            <div class="p-5 text-center">
                <div class="mb-3">
                    <i class="fas fa-ticket-alt text-muted opacity-25" style="font-size: 4rem;"></i>
                </div>
                <h5 class="fw-bold text-dark">No Tickets Yet</h5>
                <p class="text-muted small">If you have issues with a service visit, you can raise a ticket from the <a href="{{ route('user.slots') }}" class="text-primary fw-bold">My Slots</a> page.</p>
            </div>
            @endforelse
        </div>

        @if($tickets->hasPages())
        <div class="card-footer bg-white border-0 py-4 px-4 d-flex justify-content-center custom-pagination">
            {{ $tickets->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .hover-light:hover { background-color: #f8fafc; }
    .transition-all { transition: all 0.2s ease-in-out; }
    
    /* Pagination Styling (Same as Slots) */
    .custom-pagination .pagination { gap: 8px; margin-bottom: 0; }
    .custom-pagination .page-item .page-link {
        border-radius: 12px !important;
        border: 1px solid #f1f5f9;
        color: #64748b;
        font-weight: 700;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
        box-shadow: 0 5px 15px -3px rgba(113, 187, 178, 0.4);
    }
    .custom-pagination .page-item .page-link:hover:not(.active) {
        background-color: #f1f5f9;
        color: var(--primary);
        border-color: var(--primary);
    }
</style>
@endpush
@endsection
