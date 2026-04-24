@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <h2 class="content-header-title float-start mb-0">Pending service slots</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Solar slots</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer (user)</th>
                                    <th>Date</th>
                                    <th>Plan</th>
                                    <th>Assign to</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slots as $slot)
                                <tr>
                                    <td>{{ $slot->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $slot->user?->name }}</div>
                                        <small class="text-muted">{{ $slot->user?->mobile }}</small>
                                    </td>
                                    <td>{{ $slot->service_date?->format('d M, Y') }}</td>
                                    <td>{{ $slot->subscription?->package?->name }}</td>
                                    <td>
                                        <form method="post" action="{{ route('admin.solar.slots.assign') }}" class="d-flex gap-1 flex-wrap">
                                            @csrf
                                            <input type="hidden" name="slot_id" value="{{ $slot->id }}">
                                            <select name="assigned_to" class="form-select form-select-sm" style="min-width: 180px;" required>
                                                <option value="">Select admin</option>
                                                @foreach($admins as $a)
                                                    <option value="{{ $a->id }}">{{ $a->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary">Assign</button>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted">No pending slots.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $slots->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
