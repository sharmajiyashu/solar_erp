@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row mb-2">
            <div class="col-12">
                <h2 class="content-header-title">My services</h2>
                <p class="text-muted small mb-0">Assigned visits (<code>ServiceSlot</code>): customer (user), complete visit, and customer rating after they review you.</p>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">My services</li>
                </ol>
            </div>
        </div>
        <div class="content-body">
            <div class="card mb-2">
                <div class="card-body py-2">
                    <form method="get" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small mb-0">From date</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from', now()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small mb-0">To date</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to', now()->addDays(14)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Slot</th>
                                    <th>Customer (user)</th>
                                    <th>Service date</th>
                                    <th>Status</th>
                                    <th>Customer rating</th>
                                    <th>Verify / complete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slots as $slot)
                                <tr>
                                    <td class="text-muted small">#{{ $slot->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $slot->user?->name ?? '—' }}</div>
                                        <small class="text-muted d-block">{{ $slot->user?->mobile ?? '—' }}</small>
                                        <small class="text-muted">{{ $slot->user?->email ?? '—' }}</small>
                                    </td>
                                    <td>{{ $slot->service_date?->format('d M, Y') }}</td>
                                    <td>
                                        @if($slot->status === \App\Models\ServiceSlot::STATUS_ASSIGNED)
                                            <span class="badge bg-primary">Assigned</span>
                                        @elseif($slot->status === \App\Models\ServiceSlot::STATUS_COMPLETED)
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $slot->status }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($slot->technicianReview)
                                            <span class="text-warning">@for($i = 1; $i <= 5; $i++)<i class="bi bi-star{{ $i <= $slot->technicianReview->rating ? '-fill' : '' }}"></i>@endfor</span>
                                            <small class="d-block text-muted">{{ \Str::limit($slot->technicianReview->comment, 40) }}</small>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($slot->status === \App\Models\ServiceSlot::STATUS_ASSIGNED)
                                            <button type="button" class="btn btn-sm btn-success btn-complete-slot" data-slot-id="{{ $slot->id }}">
                                                <i class="bi bi-key-fill me-1"></i> Enter customer code
                                            </button>
                                        @elseif($slot->status === \App\Models\ServiceSlot::STATUS_COMPLETED)
                                            <span class="badge bg-light-success text-success border border-success border-opacity-25">
                                                <i class="bi bi-check-all me-1"></i> Done
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted py-5">
                                    <div class="mb-2"><i class="bi bi-calendar-x fs-1 opacity-25"></i></div>
                                    No assigned visits in this date range.
                                </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer border-0 bg-transparent">{{ $slots->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="completeSlotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-success text-white border-0 py-2">
                <h5 class="modal-title text-white d-flex align-items-center">
                    <i class="bi bi-check2-circle me-2 fs-4"></i> Complete Visit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="completeSlotForm" method="post" action="{{ route('admin.solar.slots.complete') }}">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">The customer reads the 6-character code from their portal; enter it below to verify and complete this service.</p>
                    
                    <input type="hidden" name="slot_id" id="modal_slot_id">
                    
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-uppercase tracking-wider text-muted">Verification Code</label>
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock text-success"></i></span>
                            <input type="text" name="verification_code" id="modal_verification_code" 
                                class="form-control text-uppercase fw-bolder border-start-0 ps-0" 
                                placeholder="******" 
                                style="letter-spacing: 0.5rem;"
                                maxlength="6" required autocomplete="off">
                        </div>
                        <div class="form-text mt-3 d-flex align-items-center text-success bg-success bg-opacity-10 p-2 rounded-3">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <span>Enter the 6-character code provided by the customer.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4 py-2 shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> Mark as Completed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-complete-slot').on('click', function() {
        const slotId = $(this).data('slot-id');
        $('#modal_slot_id').val(slotId);
        $('#modal_verification_code').val('');
        const modal = new bootstrap.Modal(document.getElementById('completeSlotModal'));
        modal.show();
        
        // Focus input after modal is shown
        document.getElementById('completeSlotModal').addEventListener('shown.bs.modal', function () {
            $('#modal_verification_code').focus();
        });
    });

    // Handle validation errors - reopen modal if slot_id exists in old input
    @if($errors->any() && old('slot_id'))
        $('#modal_slot_id').val("{{ old('slot_id') }}");
        $('#modal_verification_code').val("{{ old('verification_code') }}").addClass('is-invalid');
        const errorModal = new bootstrap.Modal(document.getElementById('completeSlotModal'));
        errorModal.show();
        
        // Show error message
        const errorMessage = "{{ $errors->first() }}";
        if (errorMessage) {
            $('#modal_verification_code').after('<div class="invalid-feedback">' + errorMessage + '</div>');
        }
    @endif
});
</script>
@endpush
