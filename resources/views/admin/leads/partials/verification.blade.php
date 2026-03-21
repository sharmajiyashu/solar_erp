@can('verification_management create')
    <form action="{{ route('admin.verification.store', $lead->id) }}" method="POST">

        @csrf

        <div class="modal-body">

            <div class="row">

                <!-- Verified By -->
                <div class="col-md-6 mb-2">
                    <label>Verified By</label>
                    <input type="text" name="verified_by_manual" class="form-control" value="{{ optional($lead->verificationReport)->verified_by_manual }}">
                </div>

                <!-- Verification Date -->
                <div class="col-md-6 mb-2">
                    <label>Verification Date</label>

                    <input type="date" name="verification_date" class="form-control"
                        value="{{ optional($lead->verificationReport)->verification_date }}">
                </div>

                <!-- Checkbox Tracking -->
                <div class="col-md-12 mb-3">
                    <h6 class="text-primary mb-2">Verification Tracking</h6>
                    
                    <div class="form-check form-switch mb-1">
                        <input class="form-check-input" type="checkbox" name="is_docs_proceed_for_2nd_tranch" id="is_docs_proceed_for_2nd_tranch" {{ optional($lead->verificationReport)->is_docs_proceed_for_2nd_tranch ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_docs_proceed_for_2nd_tranch">1. Docs proceed for bank 2nd tranch</label>
                    </div>

                    <div class="form-check form-switch mb-1">
                        <input class="form-check-input" type="checkbox" name="second_tier_payment_received" id="second_tier_payment_received" {{ optional($lead->verificationReport)->second_tier_payment_received ? 'checked' : '' }}>
                        <label class="form-check-label" for="second_tier_payment_received">2. 2nd tranch received</label>
                    </div>

                    <div class="form-check form-switch mb-1">
                        <input class="form-check-input" type="checkbox" name="is_subsidy_received" id="is_subsidy_received" {{ optional($lead->verificationReport)->is_subsidy_received ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_subsidy_received">3. Subsidy Received</label>
                    </div>

                    <div class="form-check form-switch mb-1">
                        <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified" {{ optional($lead->verificationReport)->is_verified ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_verified">4. Verified</label>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="col-md-12 mb-2">
                    <label>Remarks</label>

                    <textarea name="remarks" class="form-control">{{ optional($lead->verificationReport)->remarks }}</textarea>

                </div>

            </div>

        </div>

        <div class="modal-footer">

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Close
            </button>

            <button class="btn btn-success">
                Save Verification
            </button>

        </div>

    </form>
@else
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 mb-2">
                <label>Verified By</label>
                <p><strong>{{ optional($lead->verificationReport)->verified_by_manual ?: (optional($lead->verificationReport)->verifiedUser->name ?? 'N/A') }}</strong></p>
            </div>
            <div class="col-md-6 mb-2">
                <label>Verification Date</label>
                <p><strong>{{ optional($lead->verificationReport)->verification_date ?? 'N/A' }}</strong></p>
            </div>
            <div class="col-md-12 mb-2">
                <label class="d-block mb-1">Verification Tracking</label>
                <div class="d-flex flex-wrap gap-3">
                    <p class="mb-0">Docs for 2nd Tranch: <strong>{{ optional($lead->verificationReport)->is_docs_proceed_for_2nd_tranch ? 'Done' : 'Pending' }}</strong></p>
                    <p class="mb-0">2nd Tranch: <strong>{{ optional($lead->verificationReport)->second_tier_payment_received ? 'Received' : 'Pending' }}</strong></p>
                    <p class="mb-0">Subsidy: <strong>{{ optional($lead->verificationReport)->is_subsidy_received ? 'Received' : 'Pending' }}</strong></p>
                    <p class="mb-0">Verification: <strong>{{ optional($lead->verificationReport)->is_verified ? 'Verified' : 'Pending' }}</strong></p>
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <label>Remarks</label>
                <p>{{ optional($lead->verificationReport)->remarks ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
@endcan
@if($lead->stage == 'verification')
    @can('verification_management create')
        <div class="card border-success mt-4">
            <div class="card-body text-center">
                <h6 class="text-success mb-3">Project Completion Transition</h6>
                <p>The lead can be moved to the Completed stage once all verification steps are finished:</p>
                
                <div class="d-flex justify-content-center flex-wrap gap-3 mb-3">
                    <div>
                        <span class="badge {{ optional($lead->verificationReport)->is_docs_proceed_for_2nd_tranch ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ optional($lead->verificationReport)->is_docs_proceed_for_2nd_tranch ? 'fa-check-circle' : 'fa-times-circle' }}"></i> 
                            Docs for 2nd Tranch
                        </span>
                    </div>
                    <div>
                        <span class="badge {{ optional($lead->verificationReport)->second_tier_payment_received ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ optional($lead->verificationReport)->second_tier_payment_received ? 'fa-check-circle' : 'fa-times-circle' }}"></i> 
                            2nd Tranch Received
                        </span>
                    </div>
                    <div>
                        <span class="badge {{ optional($lead->verificationReport)->is_subsidy_received ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ optional($lead->verificationReport)->is_subsidy_received ? 'fa-check-circle' : 'fa-times-circle' }}"></i> 
                            Subsidy Received
                        </span>
                    </div>
                    <div>
                        <span class="badge {{ optional($lead->verificationReport)->is_verified ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ optional($lead->verificationReport)->is_verified ? 'fa-check-circle' : 'fa-times-circle' }}"></i> 
                            Verified
                        </span>
                    </div>
                </div>

                @php
                    $isAllDone = optional($lead->verificationReport)->is_docs_proceed_for_2nd_tranch && 
                                 optional($lead->verificationReport)->second_tier_payment_received && 
                                 optional($lead->verificationReport)->is_subsidy_received && 
                                 optional($lead->verificationReport)->is_verified;
                @endphp

                <a href="{{ route('admin.leads.move_stage', [$lead->id, 'completed']) }}" 
                   class="btn btn-lg btn-success {{ !$isAllDone ? 'disabled' : '' }}">
                    Move to Completed
                </a>
                
                @if(!$isAllDone)
                    <div class="mt-2 text-danger small">
                        <i class="fas fa-exclamation-triangle"></i> Please complete all verification tracking steps above to enable this button.
                    </div>
                @endif
            </div>
        </div>
    @endcan
@endif
