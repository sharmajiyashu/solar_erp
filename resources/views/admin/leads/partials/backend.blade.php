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
                            <label class="form-check-label" for="discom_pms_portal_login_done">Discom/PMS Portal Login Done</label>
                        </div>
                    </div>
                    @if($lead->lead_type == 'loan')
                    <div class="col-md-3">
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="bank_login_done" id="bank_login_done" {{ $lead->bank_login_done ? 'checked' : '' }}>
                            <label class="form-check-label" for="bank_login_done">Bank Login Done</label>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-3">
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="first_payment_received" id="backend_first_payment_received" {{ $lead->first_payment_received ? 'checked' : '' }}>
                            <label class="form-check-label" for="backend_first_payment_received">First Payment Received</label>
                        </div>
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="submit" class="btn btn-info btn-sm">Update Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @else
        <div class="card mb-3 border-info">
            <div class="card-body">
                <h6 class="card-title text-info">Stage Tracking (View Only)</h6>
                <div class="row">
                    <div class="col-md-4">
                        <p class="mb-0">Portal Login: <strong>{{ $lead->discom_pms_portal_login_done ? 'Done' : 'Pending' }}</strong></p>
                    </div>
                    @if($lead->lead_type == 'loan')
                    <div class="col-md-4">
                        <p class="mb-0">Bank Login: <strong>{{ $lead->bank_login_done ? 'Done' : 'Pending' }}</strong></p>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <p class="mb-0">First Payment: <strong>{{ $lead->first_payment_received ? 'Received' : 'Pending' }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="card mb-3 border-info">
        <div class="card-body">
            <h6 class="card-title text-info">Stage Tracking</h6>
            <div class="row">
                <div class="col-md-4">
                    <p class="mb-0">Portal Login: <strong>{{ $lead->discom_pms_portal_login_done ? 'Done' : 'Pending' }}</strong></p>
                </div>
                @if($lead->lead_type == 'loan')
                <div class="col-md-4">
                    <p class="mb-0">Bank Login: <strong>{{ $lead->bank_login_done ? 'Done' : 'Pending' }}</strong></p>
                </div>
                @endif
                <div class="col-md-4">
                    <p class="mb-0">First Payment: <strong>{{ $lead->first_payment_received ? 'Received' : 'Pending' }}</strong></p>
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
