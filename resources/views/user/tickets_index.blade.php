@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-black mb-0">My tickets</h3>
        <a href="{{ route('user.slots') }}" class="btn btn-outline-secondary rounded-pill">Back to slots</a>
    </div>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="list-group list-group-flush">
            @forelse($tickets as $t)
            <a href="{{ route('user.tickets.show', $t) }}" class="list-group-item list-group-item-action border-0 py-3 mb-1">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold text-dark h6 mb-0">{{ $t->subject }}</span>
                    @php
                        $color = match($t->status) {
                            'open' => 'danger',
                            'in_progress' => 'primary',
                            'closed' => 'success',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $color }} rounded-pill px-2" style="font-size: 0.7rem;">{{ strtoupper($t->status) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> Visit: {{ $t->slot?->service_date?->format('d M Y') }}</small>
                    <small class="text-muted">{{ $t->created_at?->diffForHumans() }}</small>
                </div>
            </a>
            @empty
            <div class="p-5 text-center text-muted">
                <i class="bi bi-ticket-perforated h1 d-block opacity-25"></i>
                No tickets yet. Open one from your slots page.
            </div>
            @endforelse
        </div>
        @if($tickets->hasPages())
        <div class="card-body border-top">{{ $tickets->links() }}</div>
        @endif
    </div>
</div>
@endsection
