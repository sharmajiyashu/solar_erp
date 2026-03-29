@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Expenses</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Expenses List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header border-bottom py-1 d-flex justify-content-between align-items-center flex-wrap">
                                    <h4 class="card-title fw-bold">Expenses for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h4>
                                    
                                    <div class="d-flex align-items-center flex-wrap">
                                        <form action="{{ route('admin.expenses.index') }}" method="GET" class="d-flex align-items-center me-1 mb-1 mb-md-0">
                                            <select name="month" class="form-select form-select-sm me-50" style="width: 120px;">
                                                @foreach(range(1, 12) as $m)
                                                    <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <select name="year" class="form-select form-select-sm me-50" style="width: 100px;">
                                                @foreach(range(date('Y')-5, date('Y')+1) as $y)
                                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                                        </form>

                                        <div class="me-1">
                                            <span class="badge bg-success" style="font-size: 1rem;">Wallet: ₹{{ number_format(Auth::user()->wallet_balance, 2) }}</span>
                                        </div>

                                        @can('expenses create')
                                            <a href="{{ route('admin.expenses.export', ['month' => $month, 'year' => $year]) }}" class="btn btn-sm btn-outline-success me-50">
                                                <i data-feather="download" class="me-25"></i> Export CSV
                                            </a>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">Add Expense</button>
                                        @endcan
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead style="background-color: #f8f9fa;">
                                                <tr>
                                                    <th class="ps-2 py-1" style="width: 80px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">#</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Date</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">User</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Description</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Amount</th>
                                                    <th class="py-1 text-center pe-2" style="width: 150px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($expenses as $expense)
                                                    <tr style="transition: all 0.2s ease;">
                                                        <td class="ps-2 py-1 align-middle">
                                                            <span class="text-muted fw-bold">{{ ($expenses->currentPage() - 1) * $expenses->perPage() + $loop->iteration }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="fw-bolder" style="color: #444;">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="fw-bold">{{ $expense->user->name }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span>{{ $expense->description }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="fw-bolder text-danger">₹{{ number_format($expense->amount, 2) }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle text-center pe-2">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                @can('expenses delete')
                                                                    <button type="button" class="btn btn-icon btn-flat-danger open-delete-modal" 
                                                                        data-action="{{ route('admin.expenses.destroy', $expense->id) }}" 
                                                                        title="Delete">
                                                                        <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                                                    </button>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="6" class="text-center p-2">No expenses found for this period.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="px-2 py-1 border-top d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            Showing {{ $expenses->firstItem() }} to {{ $expenses->lastItem() }} of {{ $expenses->total() }} entries
                                        </div>
                                        <div>
                                            @include('admin._pagination', ['data' => $expenses])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @can('expenses create')
        <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addExpenseModalLabel">Add New Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.expenses.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-1">
                                <label class="form-label">Expense Amount (₹)</label>
                                <input type="number" step="0.01" name="amount" class="form-control" placeholder="Enter amount..." required value="{{ old('amount') }}">
                            </div>

                            <div class="mb-1">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" placeholder="What was this expense for?" required rows="2">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-1">
                                <label class="form-label">Date</label>
                                <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Expense</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteExpenseModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteExpenseForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this expense? The amount will be automatically added back to the user's wallet.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).on('click', '.open-delete-modal', function() {
            let action = $(this).data('action');
            $('#deleteExpenseForm').attr('action', action);
            var myModal = new bootstrap.Modal(document.getElementById('deleteExpenseModal'));
            myModal.show();
        });
    </script>
    @endpush
@endsection
