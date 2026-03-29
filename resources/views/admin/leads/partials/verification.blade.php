@can('verification_management create')
    <form action="{{ route('admin.verification.store', $lead->id) }}" method="POST">
        @csrf
        
        <!-- Summary Alert -->
        <div class="alert alert-light-primary mb-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h6 class="mb-0">Procurement Items Cost: <span class="fw-bolder">₹{{ number_format($lead->procurementItems->sum('total'), 2) }}</span></h6>
                    <small>Summary of all parts dispatched to this project.</small>
                </div>
                <div class="text-end">
                    <h6 class="mb-0">Quotation Price: <span class="fw-bolder text-info">₹{{ number_format(optional($lead->verificationReport)->quotation_price ?? (optional($lead->quotations->first())->total_with_gst ?? 0), 2) }}</span></h6>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- LEFT COLUMN: Payment Processing -->
            <div class="col-md-8">
                <div class="card border shadow-none mb-2">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0"><i data-feather="dollar-sign"></i> Payment & Financial Tracking</h6>
                    </div>
                    <div class="card-body pt-2">
                        <!-- Initial Payments -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="border rounded p-1 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="fw-bold text-dark mb-0">Token Money</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="token_received" id="v_token_received" {{ $lead->token_received ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" step="0.01" name="token_amount" class="form-control" placeholder="Token Amount" value="{{ $lead->token_amount }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-1 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="fw-bold text-dark mb-0">1st Tranche Payment</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="first_payment_received" id="v_first_received" {{ $lead->first_payment_received ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="row g-50">
                                        <div class="col-7">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" step="0.01" name="first_tranche_amount" class="form-control" placeholder="1st Amount" value="{{ $lead->first_tranche_amount }}">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <input type="date" name="first_tranche_date" class="form-control" title="Received Date" value="{{ optional($lead->verificationReport)->first_tranche_date ? \Carbon\Carbon::parse($lead->verificationReport->first_tranche_date)->format('Y-m-d') : '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Payments -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="border rounded p-1 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="fw-bold text-dark mb-0">2nd Tranche Payment</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="second_tier_payment_received" id="v_second_received" {{ optional($lead->verificationReport)->second_tier_payment_received ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" step="0.01" name="second_tranche_amount" class="form-control" placeholder="2nd Amount" value="{{ optional($lead->verificationReport)->second_tranche_amount }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-1 h-100 bg-light-secondary border-secondary" style="background-color: #f8f8f8;">
                                    <label class="fw-bold text-dark mb-1">Quotation Update</label>
                                    <div class="input-group input-group-merge shadow-sm">
                                        <span class="input-group-text bg-white">₹</span>
                                        <input type="number" step="0.01" name="quotation_price" class="form-control" value="{{ optional($lead->verificationReport)->quotation_price ?? (optional($lead->quotations->first())->total_with_gst ?? 0) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice & Payout -->
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-bold">Tax Invoice Amount</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="tax_invoice_amount" class="form-control" value="{{ optional($lead->verificationReport)->tax_invoice_amount }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-bold">Payout Amount</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" name="payout_amount" class="form-control" value="{{ optional($lead->verificationReport)->payout_amount }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Verification Meta -->
            <div class="col-md-4">
                <div class="card border shadow-none mb-2">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0"><i data-feather="clipboard"></i> Official Verification</h6>
                    </div>
                    <div class="card-body pt-2">
                        <div class="mb-1">
                            <label class="form-label">Verified By</label>
                            <input type="text" name="verified_by_manual" class="form-control" value="{{ optional($lead->verificationReport)->verified_by_manual }}">
                        </div>
                        <div class="mb-1">
                            <label class="form-label">Verification Date</label>
                            <input type="date" name="verification_date" class="form-control" value="{{ optional($lead->verificationReport)->verification_date ? \Carbon\Carbon::parse($lead->verificationReport->verification_date)->format('Y-m-d') : '' }}">
                        </div>

                        <div class="divider divider-left">
                            <div class="divider-text text-primary fw-bold">Stage Tracking</div>
                        </div>

                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" name="is_docs_proceed_for_2nd_tranch" id="v_docs_proceed" {{ optional($lead->verificationReport)->is_docs_proceed_for_2nd_tranch ? 'checked' : '' }}>
                            <label class="form-check-label" for="v_docs_proceed">Documentation Done</label>
                        </div>
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" name="is_subsidy_received" id="v_subsidy" {{ optional($lead->verificationReport)->is_subsidy_received ? 'checked' : '' }}>
                            <label class="form-check-label" for="v_subsidy">Subsidy Received</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_verified" id="v_is_verified" {{ optional($lead->verificationReport)->is_verified ? 'checked' : '' }}>
                            <label class="form-check-label text-success fw-bolder" for="v_is_verified">Verification Complete</label>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-1">
                            <i data-feather="check-square"></i> Save All Changes
                        </button>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            <div class="col-12 mt-1">
                <div class="card border shadow-none">
                    <div class="card-body py-1">
                        <label class="form-label fw-bold">Verification Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Enter any additional notes here...">{{ optional($lead->verificationReport)->remarks }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
@else
    <!-- Summary View (Read Only) -->
    <div class="card border-primary shadow-none mb-2">
        <div class="card-body p-2">
            <h6 class="text-primary border-bottom pb-50 mb-1"><i data-feather="info"></i> Financial Summary (Read Only)</h6>
            <div class="row text-center">
                <div class="col-md-3 border-end">
                    <small class="text-muted d-block">Quotation</small>
                    <h5 class="fw-bold">₹{{ number_format(optional($lead->verificationReport)->quotation_price ?? 0, 2) }}</h5>
                </div>
                <div class="col-md-3 border-end">
                    <small class="text-muted d-block">Procurement</small>
                    <h5 class="fw-bold">₹{{ number_format($lead->procurementItems->sum('total'), 2) }}</h5>
                </div>
                <div class="col-md-3 border-end">
                    <small class="text-muted d-block">Tax Invoice</small>
                    <h5 class="fw-bold">₹{{ number_format(optional($lead->verificationReport)->tax_invoice_amount ?? 0, 2) }}</h5>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Payout</small>
                    <h5 class="fw-bold text-success">₹{{ number_format(optional($lead->verificationReport)->payout_amount ?? 0, 2) }}</h5>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-4 mb-1">
                    <div class="alert alert-{{ $lead->token_received ? 'success' : 'secondary' }} m-0 p-1">
                        <div class="d-flex justify-content-between">
                            <span>Token</span>
                            <span class="fw-bold">₹{{ number_format($lead->token_amount ?? 0, 2) }}</span>
                        </div>
                        <small>{{ $lead->token_received ? 'Received' : 'Pending' }}</small>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="alert alert-{{ $lead->first_payment_received ? 'info' : 'secondary' }} m-0 p-1">
                        <div class="d-flex justify-content-between">
                            <span>1st Tranche</span>
                            <span class="fw-bold">₹{{ number_format($lead->first_tranche_amount ?? 0, 2) }}</span>
                        </div>
                        <small>
                            {{ $lead->first_payment_received ? 'Received' : 'Pending' }}
                            @if($lead->first_payment_received && optional($lead->verificationReport)->first_tranche_date)
                                on {{ \Carbon\Carbon::parse(optional($lead->verificationReport)->first_tranche_date)->format('d M y') }}
                            @endif
                        </small>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="alert alert-{{ optional($lead->verificationReport)->second_tier_payment_received ? 'primary' : 'secondary' }} m-0 p-1">
                        <div class="d-flex justify-content-between">
                            <span>2nd Tranche</span>
                            <span class="fw-bold">₹{{ number_format(optional($lead->verificationReport)->second_tranche_amount ?? 0, 2) }}</span>
                        </div>
                        <small>{{ optional($lead->verificationReport)->second_tier_payment_received ? 'Received' : 'Pending' }}</small>
                    </div>
                </div>
            </div>

            <div class="row mt-1 px-1">
                <div class="col-md-6">
                    <p class="mb-25">Verified By: <strong>{{ optional($lead->verificationReport)->verified_by_manual ?: (optional($lead->verificationReport)->verifier->name ?? 'N/A') }}</strong></p>
                    <p class="mb-25">Verification Date: <strong>{{ optional($lead->verificationReport)->verification_date ? \Carbon\Carbon::parse($lead->verificationReport->verification_date)->format('d M Y') : 'N/A' }}</strong></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-0 text-muted">Remarks:</p>
                    <p class="m-0">{{ optional($lead->verificationReport)->remarks ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
@endcan

<!-- Transition to Completed -->
@if($lead->stage == 'verification')
    @can('verification_management create')
        <div class="card border-success mt-1 shadow-none">
            <div class="card-body text-center bg-light-success rounded">
                <h6 class="text-success fw-bold mb-2">Project Completion Transition</h6>
                
                <div class="d-flex justify-content-center flex-wrap gap-2 mb-2">
                    <div class="badge {{ optional($lead->verificationReport)->is_docs_proceed_for_2nd_tranch ? 'bg-success' : 'bg-secondary opacity-50' }} p-1">
                        <i class="fas {{ optional($lead->verificationReport)->is_docs_proceed_for_2nd_tranch ? 'fa-check' : 'fa-clock' }} me-50"></i> Docs
                    </div>
                    <div class="badge {{ optional($lead->verificationReport)->second_tier_payment_received ? 'bg-success' : 'bg-secondary opacity-50' }} p-1">
                        <i class="fas {{ optional($lead->verificationReport)->second_tier_payment_received ? 'fa-check' : 'fa-clock' }} me-50"></i> 2nd Tranche
                    </div>
                    <div class="badge {{ optional($lead->verificationReport)->is_subsidy_received ? 'bg-success' : 'bg-secondary opacity-50' }} p-1">
                        <i class="fas {{ optional($lead->verificationReport)->is_subsidy_received ? 'fa-check' : 'fa-clock' }} me-50"></i> Subsidy
                    </div>
                    <div class="badge {{ optional($lead->verificationReport)->is_verified ? 'bg-success' : 'bg-secondary opacity-50' }} p-1">
                        <i class="fas {{ optional($lead->verificationReport)->is_verified ? 'fa-check' : 'fa-clock' }} me-50"></i> Verified
                    </div>
                </div>

                @php
                    $isAllDone = optional($lead->verificationReport)->is_docs_proceed_for_2nd_tranch && 
                                 optional($lead->verificationReport)->second_tier_payment_received && 
                                 optional($lead->verificationReport)->is_subsidy_received && 
                                 optional($lead->verificationReport)->is_verified;
                @endphp

                <a href="{{ route('admin.leads.move_stage', [$lead->id, 'completed']) }}" 
                   class="btn btn-success btn-lg {{ !$isAllDone ? 'disabled' : '' }} shadow-sm">
                    Move to Completed Stage
                </a>
                
                @if(!$isAllDone)
                    <div class="mt-1 text-danger small fst-italic">
                        <i class="fas fa-exclamation-circle"></i> Complete all milestones above to finish this project.
                    </div>
                @endif
            </div>
        </div>
    @endcan
@endif
