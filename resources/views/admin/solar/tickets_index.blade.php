@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row mb-2">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="content-header-title mb-0">Tickets</h2>
                <form method="get" class="d-flex gap-1">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All statuses</option>
                        <option value="open" @selected(request('status')==='open')>Open</option>
                        <option value="in_progress" @selected(request('status')==='in_progress')>In progress</option>
                        <option value="closed" @selected(request('status')==='closed')>Closed</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>ID</th><th>Subject</th><th>User</th><th>Slot</th><th>Status</th><th></th></tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->subject }}</td>
                                <td>{{ $t->user?->name }}</td>
                                <td>{{ $t->slot?->service_date?->format('Y-m-d') }}</td>
                                <td><span class="badge bg-secondary">{{ $t->status }}</span></td>
                                <td><a href="{{ route('admin.solar.tickets.show', $t) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
