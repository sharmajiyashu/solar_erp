@extends('user.layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-black mb-0">My tickets</h3>
        <a href="{{ route('user.slots') }}" class="btn btn-outline-secondary rounded-pill">Back to slots</a>
    </div>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="list-group list-group-flush">
            @forelse($tickets as $t)
            <a href="{{ route('user.tickets.show', $t) }}" class="list-group-item list-group-item-action py-3">
                <div class="d-flex justify-content-between">
                    <strong>{{ $t->subject }}</strong>
                    <span class="badge bg-secondary">{{ $t->status }}</span>
                </div>
                <small class="text-muted">Slot {{ $t->slot?->service_date?->format('d M Y') }}</small>
            </a>
            @empty
            <div class="p-4 text-center text-muted">No tickets yet. Open one from a slot on the slots page.</div>
            @endforelse
        </div>
        <div class="card-body border-top">{{ $tickets->links() }}</div>
    </div>
</div>
@endsection
