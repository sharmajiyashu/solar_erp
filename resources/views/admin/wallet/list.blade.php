@extends('admin.layouts.app')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Wallets Management</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">All Admin Wallets</li>
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
                            <div class="card">
                                <div class="card-header border-bottom">
                                    <h4 class="card-title">All Admin Balances</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead style="background-color: #f8f9fa;">
                                                <tr>
                                                    <th class="ps-2 py-1" style="width: 80px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">#</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Name</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Email</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Current Balance</th>
                                                    <th class="py-1 text-center pe-2" style="width: 250px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $u)
                                                    <tr style="transition: all 0.2s ease;">
                                                        <td class="ps-2 py-1 align-middle">
                                                            <span class="text-muted fw-bold">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="fw-bolder" style="color: #444;">{{ $u->name }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle text-muted">
                                                            {{ $u->email }}
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="badge bg-success" style="font-size: 0.9rem;">₹{{ number_format($u->wallet_balance, 2) }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle text-center pe-2">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <a href="{{ route('admin.wallet_history', $u->id) }}" class="btn btn-sm btn-outline-info me-50" title="History">
                                                                     History
                                                                </a>
                                                                @can('wallet manage')
                                                                    <button type="button" class="btn btn-sm btn-primary py-1" onclick="openManageModal('{{ $u->id }}', '{{ $u->name }}')">
                                                                        Manage
                                                                    </button>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="px-2 py-1 border-top d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                                        </div>
                                        <div>
                                            @include('admin._pagination', ['data' => $users])
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
                        <h5 class="modal-title" id="manageWalletModalLabel">Manage Wallet for <span id="targetUserName"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.wallet.addBudget') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="user_id" id="targetUserId">
                            
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

        <script>
            function openManageModal(userId, userName) {
                document.getElementById('targetUserId').value = userId;
                document.getElementById('targetUserName').innerText = userName;
                var myModal = new bootstrap.Modal(document.getElementById('manageWalletModal'));
                myModal.show();
            }
        </script>
    @endcan
@endsection
