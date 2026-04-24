@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row mb-2">
            <div class="col-12">
                <h2 class="content-header-title">Customer reviews (technicians)</h2>
                <p class="text-muted small mb-0">Ratings submitted by customers after completed visits.</p>
            </div>
        </div>
        <div class="content-body">
            <div class="card mb-2">
                <div class="card-body">
                    <form method="get" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small">Customer</label>
                            <select name="user_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Technician</label>
                            <select name="technician_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach($technicians as $t)
                                    <option value="{{ $t->id }}" @selected(request('technician_id') == $t->id)>{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-sm w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Technician</th>
                                <th>Visit</th>
                                <th>Rating</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $row)
                            <tr>
                                <td>{{ $row->created_at?->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $row->customer?->name }}</div>
                                    <small class="text-muted">{{ $row->customer?->email }}</small>
                                </td>
                                <td>{{ $row->technician?->name }}</td>
                                <td>{{ $row->slot?->service_date?->format('Y-m-d') }} <small class="text-muted">#{{ $row->slot_id }}</small></td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $row->rating ? '-fill text-warning' : '' }}"></i>
                                    @endfor
                                </td>
                                <td>{{ \Str::limit($row->comment, 100) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No customer reviews yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
