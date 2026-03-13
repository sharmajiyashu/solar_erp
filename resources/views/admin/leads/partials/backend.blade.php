<div class="d-flex justify-content-between mb-2">
    <h5>Backend Management</h5>
</div>

<div class="card mb-3 border-info">
    <div class="card-body">
        <h6 class="card-title text-info">Stage Tracking</h6>
        <form action="{{ route('admin.backend-tracking.tracking', $lead->id) }}" method="POST">
            @csrf
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="first_payment_received" id="backend_first_payment_received" {{ $lead->first_payment_received ? 'checked' : '' }}>
                        <label class="form-check-label" for="backend_first_payment_received">First Payment Received</label>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-info btn-sm">Update Status</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-warning">
    <div class="card-body text-center">
        <p>Once the first payment is received, you can move the lead to the Procurement stage.</p>
        
        <form action="{{ route('admin.backend-tracking.move', $lead->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-lg btn-warning" {{ !$lead->first_payment_received ? 'disabled' : '' }}>
                Move to Procurement
            </button>
        </form>
        
        @if(!$lead->first_payment_received)
            <div class="mt-2 text-danger small">
                <i class="fas fa-exclamation-triangle"></i> Please confirm payment receipt above to enable this button.
            </div>
        @endif
    </div>
</div>
