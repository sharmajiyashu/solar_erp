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
                            <h2 class="content-header-title float-start mb-0">Wallet History</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    @can('wallet manage')
                                        <li class="breadcrumb-item"><a href="{{ route('admin.wallet_management') }}">All Wallets</a></li>
                                    @endcan
                                    <li class="breadcrumb-item active">Wallet history</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success p-1">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger p-1">{{ session('error') }}</div>
                @endif

                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header border-bottom py-1 d-flex justify-content-between align-items-center flex-wrap">
                                    <h4 class="card-title fw-bold">Transactions for {{ $user->name }} ({{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }})</h4>
                                    
                                    <div class="d-flex align-items-center flex-wrap">
                                        <form action="{{ Request::url() }}" method="GET" class="d-flex align-items-center me-1">
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
                                            <span class="badge bg-success" style="font-size: 1rem;">₹{{ number_format($user->wallet_balance, 2) }}</span>
                                        </div>

                                        @can('wallet manage')
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#manageWalletModal">Manage</button>
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
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Type</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Amount</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Description</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Balance After</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($transactions as $transaction)
                                                    <tr style="transition: all 0.2s ease;">
                                                        <td class="ps-2 py-1 align-middle">
                                                            <span class="text-muted fw-bold">{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="fw-bolder" style="color: #444;">{{ $transaction->created_at->format('d M Y H:i') }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="badge bg-{{ $transaction->type == 'credit' ? 'success' : 'danger' }}">
                                                                {{ ucfirst($transaction->type) }}
                                                            </span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="fw-bolder text-{{ $transaction->type == 'credit' ? 'success' : 'danger' }}">
                                                                {{ $transaction->type == 'credit' ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                                                            </span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span>{{ $transaction->description }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="fw-bold">₹{{ number_format($transaction->balance_after, 2) }}</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="6" class="text-center p-2">No transactions found.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="px-2 py-1 border-top d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} entries
                                        </div>
                                        <div>
                                            @include('admin._pagination', ['data' => $transactions])
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

    @can('wallet manage')
        <div class="modal fade" id="manageWalletModal" tabindex="-1" aria-labelledby="manageWalletModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manageWalletModalLabel">Manage Wallet for {{ $user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.wallet.addBudget') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            
                            <div class="mb-1">
                                <label class="form-label">Transaction Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="credit">Add Funds (Credit)</option>
                                    <option value="debit">Remove Funds (Debit)</option>
                                </select>
                            </div>

                            <div class="mb-1">
                                <label class="form-label">Amount (₹)</label>
                                <input type="number" step="0.01" name="amount" class="form-control" placeholder="Enter amount..." required>
                            </div>

                            <div class="mb-1">
                                <label class="form-label">Reason / Description</label>
                                <textarea name="description" class="form-control" placeholder="Provide a reason for this transaction..." required rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Wallet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
