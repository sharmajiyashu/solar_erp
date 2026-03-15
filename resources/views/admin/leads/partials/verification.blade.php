@can('verification_management create')
    @if(!($is_past_stage ?? false))
    <form action="{{ route('admin.verification.store', $lead->id) }}" method="POST">

        @csrf

        <div class="modal-body">

            <div class="row">

                <!-- Verified By -->
                <div class="col-md-6 mb-2">
                    <label>Verified By</label>
                    <input type="text" name="verified_by_manual" class="form-control" value="{{ optional($lead->verificationReport)->verified_by_manual }}">
                    
                    <div class="mt-1">
                        <label class="small text-muted">Or Select Internal User (Optional)</label>
                        <select name="verified_by" class="form-control">
                            <option value="">Select User</option>
                            @foreach (App\Models\User::all() as $user)
                                <option value="{{ $user->id }}"
                                    {{ optional($lead->verificationReport)->verified_by == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Verification Date -->
                <div class="col-md-6 mb-2">
                    <label>Verification Date</label>

                    <input type="date" name="verification_date" class="form-control"
                        value="{{ optional($lead->verificationReport)->verification_date }}">
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-2">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ optional($lead->verificationReport)->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="docs proceed for bank 2nd tranch" {{ optional($lead->verificationReport)->status == 'docs proceed for bank 2nd tranch' ? 'selected' : '' }}>1. docs proceed for bank 2nd tranch</option>
                        <option value="2nd tranch received" {{ optional($lead->verificationReport)->status == '2nd tranch received' ? 'selected' : '' }}>2. 2nd tranch received</option>
                        <option value="verified" {{ optional($lead->verificationReport)->status == 'verified' ? 'selected' : '' }}>3. verified</option>
                        <option value="rejected" {{ optional($lead->verificationReport)->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- 2nd Payment -->
                <div class="col-md-12 mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="second_tier_payment_received" id="second_tier_payment_received" {{ optional($lead->verificationReport)->second_tier_payment_received ? 'checked' : '' }}>
                        <label class="form-check-label" for="second_tier_payment_received">2nd Payment Received</label>
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

            <button class="btn btn-secondary" data-bs-dismiss="modal">
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
                <div class="col-md-6 mb-2">
                    <label>Status</label>
                    <p><strong>{{ ucfirst(optional($lead->verificationReport)->status ?? 'N/A') }}</strong></p>
                </div>
                <div class="col-md-6 mb-2">
                    <p>2nd Payment: <strong>{{ optional($lead->verificationReport)->second_tier_payment_received ? 'Received' : 'Pending' }}</strong></p>
                </div>
                <div class="col-md-12 mb-2">
                    <label>Remarks</label>
                    <p>{{ optional($lead->verificationReport)->remarks ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    @endif
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
            <div class="col-md-6 mb-2">
                <label>Status</label>
                <p><strong>{{ ucfirst(optional($lead->verificationReport)->status ?? 'N/A') }}</strong></p>
            </div>
            <div class="col-md-6 mb-2">
                <p>2nd Payment: <strong>{{ optional($lead->verificationReport)->second_tier_payment_received ? 'Received' : 'Pending' }}</strong></p>
            </div>
            <div class="col-md-12 mb-2">
                <label>Remarks</label>
                <p>{{ optional($lead->verificationReport)->remarks ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
@endcan
@if($lead->stage == 'verification' && ($lead->verificationReport->status ?? '') == 'verified')
    @can('verification_management create')
        <div class="mt-4 text-end">
            <a href="{{ route('admin.leads.move_stage', [$lead->id, 'completed']) }}" class="btn btn-lg btn-success">
                Move to Completed
            </a>
        </div>
    @endcan
@endif
