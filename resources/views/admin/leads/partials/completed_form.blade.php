<div class="d-flex justify-content-between mb-2">
    <h5>Project Completion</h5>
</div>

<form action="{{ route('admin.leads.update', $lead->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="is_completion" value="1">

    <div class="row">
        <!-- Verified By Info -->
        <div class="col-md-6 mb-2">
            <label>Verified By</label>
            <input type="text" class="form-control" readonly value="{{ optional($lead->verificationReport)->verified_by_manual ?: (optional($lead->verificationReport)->verifier->name ?? 'N/A') }}">
        </div>

        <div class="col-md-6 mb-2">
            <label>Verification Date</label>
            <input type="text" class="form-control" readonly value="{{ optional($lead->verificationReport)->verification_date ?? 'N/A' }}">
        </div>

        <!-- Handover -->
        <div class="col-md-6 mb-2">
            <label>Handover to Customer</label>
            <div class="form-check form-switch mt-1">
                <input class="form-check-input" type="checkbox" name="handover_done" id="handover_done" {{ $lead->status == 'completed' ? 'checked' : '' }}>
                <label class="form-check-label" for="handover_done">Handover Done</label>
            </div>
        </div>

        <!-- Upload Photos -->
        <div class="col-md-6 mb-2">
            <label>Upload Final Photos</label>
            <input type="file" name="completion_photos[]" class="form-control" multiple>
        </div>

        <!-- Remarks -->
        <div class="col-md-12 mb-2">
            <label>Final Remarks</label>
            <textarea name="remarks" class="form-control">{{ $lead->remarks }}</textarea>
        </div>

        <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-success">Update Completion Details</button>
        </div>
    </div>
</form>
