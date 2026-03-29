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
                        <h2 class="content-header-title float-start mb-0">Admin Expenditure Report</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Expenditure Reports</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1">
                    <a href="{{ route('admin.expense_reports.export', ['month' => $month, 'year' => $year]) }}" class="btn btn-primary">
                        <i data-feather="download"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Filter Section -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.expense_reports.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label">Month</label>
                                    <select name="month" class="form-select">
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label">Year</label>
                                    <select name="year" class="form-select">
                                        @foreach(range(date('Y')-5, date('Y')+1) as $y)
                                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 d-flex align-items-end">
                                <div class="mb-1">
                                    <button type="submit" class="btn btn-outline-primary me-1">Filter</button>
                                    <a href="{{ route('admin.expense_reports.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                                <th>Admin Name</th>
                                <th>Email</th>
                                <th>Current Balance</th>
                                <th>Added (This Month)</th>
                                <th>Expenses (This Month)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $data)
                            <tr>
                                <td><span class="fw-bold">{{ $data->name }}</span></td>
                                <td>{{ $data->email }}</td>
                                <td><span class="text-primary fw-bold">₹{{ number_format($data->current_balance, 2) }}</span></td>
                                <td><span class="text-success">₹{{ number_format($data->monthly_added, 2) }}</span></td>
                                <td><span class="text-danger">₹{{ number_format($data->monthly_expenses, 2) }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No admins found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
