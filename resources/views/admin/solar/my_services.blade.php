@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row mb-2">
            <div class="col-12">
                <h2 class="content-header-title">My services</h2>
                <p class="text-muted small mb-0">Assigned visits (<code>ServiceSlot</code>): customer (user), verification code, complete visit, and customer rating after they review you.</p>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">My services</li>
                </ol>
            </div>
        </div>
        <div class="content-body">
            <div class="card mb-2">
                <div class="card-body py-2">
                    <form method="get" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small mb-0">From date</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from', now()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small mb-0">To date</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to', now()->addDays(14)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Slot</th>
                                    <th>Customer (user)</th>
                                    <th>Service date</th>
                                    <th>Status</th>
                                    <th>Verification code</th>
                                    <th>Customer rating</th>
                                    <th>Verify / complete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slots as $slot)
                                <tr>
                                    <td class="text-muted small">#{{ $slot->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $slot->user?->name ?? '—' }}</div>
                                        <small class="text-muted d-block">{{ $slot->user?->mobile ?? '—' }}</small>
                                        <small class="text-muted">{{ $slot->user?->email ?? '—' }}</small>
                                    </td>
                                    <td>{{ $slot->service_date?->format('Y-m-d') }}</td>
                                    <td>
                                        @if($slot->status === \App\Models\ServiceSlot::STATUS_ASSIGNED)
                                            <span class="badge bg-primary">Assigned</span>
                                        @elseif($slot->status === \App\Models\ServiceSlot::STATUS_COMPLETED)
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $slot->status }}</span>
                                        @endif
                                    </td>
                                    <td><code class="user-select-all">{{ $slot->verification_code }}</code></td>
                                    <td>
                                        @if($slot->technicianReview)
                                            <span class="text-warning">@for($i = 1; $i <= 5; $i++)<i class="bi bi-star{{ $i <= $slot->technicianReview->rating ? '-fill' : '' }}"></i>@endfor</span>
                                            <small class="d-block text-muted">{{ \Str::limit($slot->technicianReview->comment, 40) }}</small>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($slot->status === \App\Models\ServiceSlot::STATUS_ASSIGNED)
                                            <a href="{{ route('admin.solar.slots.complete.form', $slot) }}" class="btn btn-sm btn-success">Enter customer code</a>
                                        @elseif($slot->status === \App\Models\ServiceSlot::STATUS_COMPLETED)
                                            <span class="small text-muted">Done</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No assigned visits in this date range.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">{{ $slots->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
