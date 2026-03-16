@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Attendance Report</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                                <li class="breadcrumb-item active">Attendance</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1">
                    @can('reports export')
                    <a href="{{ route('admin.reports.attendance.export', request()->all()) }}" class="btn btn-primary">
                        <i data-feather="download"></i> Export Excel
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Filter Section -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.attendance') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="user_id">User</label>
                                    <select class="form-select" id="user_id" name="user_id">
                                        <option value="">All Users</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="month">Month</label>
                                    <select class="form-select" id="month" name="month">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="year">Year</label>
                                    <select class="form-select" id="year" name="year">
                                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12 d-flex align-items-end">
                                <div class="mb-1">
                                    <button type="submit" class="btn btn-outline-primary me-1">Filter</button>
                                    <a href="{{ route('admin.reports.attendance') }}" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Punch In</th>
                                <th>Punch Out</th>
                                <th>Status</th>
                                <th>Photo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $record)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                                <td><span class="fw-bold">{{ $record->user->name ?? 'N/A' }}</span></td>
                                <td>
                                    @if($record->punch_in)
                                        <span class="badge badge-light-success">{{ $record->punch_in }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($record->punch_out)
                                        <span class="badge badge-light-info">{{ $record->punch_out }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($record->punch_in)
                                        <span class="badge rounded-pill badge-light-success">Present</span>
                                    @else
                                        <span class="badge rounded-pill badge-light-danger">Absent</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->punch_in_photo)
                                        <a href="{{ asset($record->punch_in_photo) }}" target="_blank">
                                            <img src="{{ asset($record->punch_in_photo) }}" width="40" height="40" class="rounded" alt="Punch In Photo">
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No attendance records found for selected period.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    @include('admin._pagination', ['data' => $attendances])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
