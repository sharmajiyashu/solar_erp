<div class="d-flex justify-content-between mb-2">
    <h5>Backend Management</h5>
</div>

@can('backend_management create')
    @if(!($is_past_stage ?? false))
    <div class="card mb-3 border-info">
        <div class="card-body">
            <h6 class="card-title text-info">Stage Tracking</h6>
            <form action="{{ route('admin.backend-tracking.tracking', $lead->id) }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="discom_pms_portal_login_done" id="discom_pms_portal_login_done" {{ $lead->discom_pms_portal_login_done ? 'checked' : '' }}>
                            <label class="form-check-label text-nowrap" for="discom_pms_portal_login_done">Portal Login Done</label>
                        </div>
                    </div>
                    @if($lead->lead_type == 'loan')
                    <div class="col-md-2">
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="bank_login_done" id="bank_login_done" {{ $lead->bank_login_done ? 'checked' : '' }}>
                            <label class="form-check-label" for="bank_login_done">Bank Login</label>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-2">
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="first_payment_received" id="backend_first_payment_received" {{ $lead->first_payment_received ? 'checked' : '' }}>
                            <label class="form-check-label text-nowrap" for="backend_first_payment_received">First Tranche Received</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" name="first_tranche_amount" class="form-control" placeholder="Tranche Amount" value="{{ $lead->first_tranche_amount }}">
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-info btn-sm w-100">Update</button>
                    </div>
                </div>
            </form>

            <div class="mt-2 pt-1 border-top border-info border-opacity-25">
                <h6 class="small fw-bold text-uppercase text-muted mb-1">Payment Summary</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Payment Stage</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-muted small">Token Amount</td>
                                <td class="text-center">
                                    @if($lead->token_received)
                                        <span class="badge bg-light-success text-success">Received</span>
                                    @else
                                        <span class="badge bg-light-warning text-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end text-muted small">₹{{ number_format($lead->token_amount ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">First Tranche</td>
                                <td class="text-center">
                                    @if($lead->first_payment_received)
                                        <span class="badge bg-light-success text-success">Received</span>
                                    @else
                                        <span class="badge bg-light-warning text-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-info">₹{{ number_format($lead->first_tranche_amount ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="card mb-3 border-info">
            <div class="card-body">
                <h6 class="card-title text-info">Stage Tracking (View Only)</h6>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <p class="mb-0 text-muted small">Portal Login</p>
                        <h6 class="mb-0 fw-bold">{{ $lead->discom_pms_portal_login_done ? 'Done' : 'Pending' }}</h6>
                    </div>
                    @if($lead->lead_type == 'loan')
                    <div class="col-md-4">
                        <p class="mb-0 text-muted small">Bank Login</p>
                        <h6 class="mb-0 fw-bold">{{ $lead->bank_login_done ? 'Done' : 'Pending' }}</h6>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <p class="mb-0 text-muted small">First Tranche</p>
                        <h6 class="mb-0 fw-bold">{{ $lead->first_payment_received ? 'Received (₹'.number_format($lead->first_tranche_amount, 2).')' : 'Pending' }}</h6>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="card mb-3 border-info">
        <div class="card-body">
            <h6 class="card-title text-info">Stage Tracking</h6>
            <div class="row align-items-center">
                <div class="col-md-4">
                    <p class="mb-0 text-muted small">Portal Login</p>
                    <h6 class="mb-0 fw-bold">{{ $lead->discom_pms_portal_login_done ? 'Done' : 'Pending' }}</h6>
                </div>
                @if($lead->lead_type == 'loan')
                <div class="col-md-4">
                    <p class="mb-0 text-muted small">Bank Login</p>
                    <h6 class="mb-0 fw-bold">{{ $lead->bank_login_done ? 'Done' : 'Pending' }}</h6>
                </div>
                @endif
                <div class="col-md-4">
                    <p class="mb-0 text-muted small">First Tranche</p>
                    <h6 class="mb-0 fw-bold">{{ $lead->first_payment_received ? 'Received (₹'.number_format($lead->first_tranche_amount, 2).')' : 'Pending' }}</h6>
                </div>
            </div>
        </div>
    </div>
@endcan

@can('backend_management create')
    @if(!($is_past_stage ?? false))
    <div class="card border-warning">
        <div class="card-body text-center">
            @php
                $isBankRequired = $lead->lead_type == 'loan';
                $canMove = $lead->first_payment_received && $lead->discom_pms_portal_login_done && (!$isBankRequired || $lead->bank_login_done);
            @endphp
            
            <p>Once all required steps are completed (Portal Login, {{ $isBankRequired ? 'Bank Login, ' : '' }}and First Payment), you can move the lead to the Procurement stage.</p>
            
            <form action="{{ route('admin.backend-tracking.move', $lead->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-lg btn-warning" {{ !$canMove ? 'disabled' : '' }}>
                    Move to Procurement
                </button>
            </form>
            
            @if(!$canMove)
                <div class="mt-2 text-danger small">
                    <i class="fas fa-exclamation-triangle"></i> Please complete all required tracking steps above to enable this button.
                </div>
            @endif
        </div>
    </div>
    @endif
@endcan
