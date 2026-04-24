@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-xxl pt-4 px-4">
        <div class="content-header row mb-3">
            <div class="col-12">
                <h2 class="content-header-title">Support Tickets</h2>
                <p class="text-muted small mb-0">Manage customer queries and real-time chat requests.</p>
            </div>
        </div>
        <div class="content-body">
            <!-- Filter Section -->
            <div class="card mb-3 shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <form method="get" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Customer</label>
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">All Customers</option>
                                @foreach($users ?? [] as $u)
                                    <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="open" @selected(request('status')==='open')>Open</option>
                                <option value="in_progress" @selected(request('status')==='in_progress')>In Progress</option>
                                <option value="closed" @selected(request('status')==='closed')>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                                <a href="{{ route('admin.solar.tickets.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3 py-2 border-0">ID</th>
                                <th class="py-2 border-0">Subject</th>
                                <th class="py-2 border-0">Customer</th>
                                <th class="py-2 border-0">Visit/Slot</th>
                                <th class="py-2 border-0 text-center">Status</th>
                                <th class="pe-3 py-2 border-0 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $t)
                            <tr class="align-middle">
                                <td class="ps-3">
                                    <span class="badge bg-light text-dark border">#{{ $t->id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $t->subject }}</div>
                                    <small class="text-muted">{{ $t->created_at?->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $t->user?->name }}</div>
                                    <small class="text-muted">{{ $t->user?->email }}</small>
                                </td>
                                <td>
                                    <div>{{ $t->slot?->service_date?->format('M d, Y') }}</div>
                                    <small class="text-muted">ID: #{{ $t->service_slot_id }}</small>
                                </td>
                                <td class="text-center">
                                    @php
                                        $color = match($t->status) {
                                            'open' => 'danger',
                                            'in_progress' => 'primary',
                                            'closed' => 'success',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }} rounded-pill px-3">
                                        {{ str_replace('_', ' ', strtoupper($t->status)) }}
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    <a href="{{ route('admin.solar.tickets.show', $t) }}" class="btn btn-sm btn-flat-primary">
                                        <i class="bi bi-chat-dots me-1"></i> Open Chat
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    No tickets found matching your filters.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tickets->hasPages())
                <div class="card-footer bg-white border-0">
                    {{ $tickets->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
