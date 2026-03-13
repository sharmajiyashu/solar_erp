@can('verification_management create')
    <form action="{{ route('admin.verification.store', $lead->id) }}" method="POST">

        @csrf

        <div class="modal-body">

            <div class="row">

                <!-- Verified By -->
                <div class="col-md-6 mb-2">
                    <label>Verified By</label>

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

                        <option value="pending" {{ optional($lead->verificationReport)->status == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="verified"
                            {{ optional($lead->verificationReport)->status == 'verified' ? 'selected' : '' }}>
                            Verified
                        </option>

                        <option value="rejected"
                            {{ optional($lead->verificationReport)->status == 'rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>

                    </select>
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
                <p><strong>{{ optional($lead->verificationReport)->verifiedUser->name ?? 'N/A' }}</strong></p>
            </div>
            <div class="col-md-6 mb-2">
                <label>Verification Date</label>
                <p><strong>{{ optional($lead->verificationReport)->verification_date ?? 'N/A' }}</strong></p>
            </div>
            <div class="col-md-6 mb-2">
                <label>Status</label>
                <p><strong>{{ ucfirst(optional($lead->verificationReport)->status ?? 'N/A') }}</strong></p>
            </div>
            <div class="col-md-12 mb-2">
                <label>Remarks</label>
                <p>{{ optional($lead->verificationReport)->remarks ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
@endcan
