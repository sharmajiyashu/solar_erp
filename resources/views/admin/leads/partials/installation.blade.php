<form action="{{ route('admin.installation.store', $lead->id) }}" method="POST">

    @csrf

    <div class="modal-body">

        <div class="row">

            <!-- Technician -->
            <div class="col-md-6 mb-1">
                <label>Technician</label>

                <select name="technician_id" class="form-control">

                    <option value="">Select Technician</option>

                    @foreach (App\Models\User::all() as $user)
                        <option value="{{ $user->id }}"
                            {{ optional($lead->installation)->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- Installation Date -->
            <div class="col-md-6 mb-1">
                <label>Installation Date</label>

                <input type="date" name="installation_date"
                    value="{{ $lead->installation->installation_date ?? '' }}" class="form-control">
            </div>

            <!-- Status -->
            <div class="col-md-6 mb-1">
                <label>Status</label>

                <select name="status" class="form-control">

                    <option value="assigned" {{ optional($lead->installation)->status == 'assigned' ? 'selected' : '' }}>
                        Assigned
                    </option>

                    <option value="in_progress"
                        {{ optional($lead->installation)->status == 'in_progress' ? 'selected' : '' }}>
                        In Progress
                    </option>

                    <option value="completed" {{ optional($lead->installation)->status == 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                    <option value="cancelled" {{ optional($lead->installation)->status == 'cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>

                </select>
            </div>

            <!-- Notes -->
            <div class="col-md-12 mb-1">
                <label>Notes</label>

                <textarea name="notes" class="form-control">{{ $lead->installation->notes ?? '' }}</textarea>

            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
            Close
        </button>

        <button class="btn btn-success">
            Save Installation
        </button>
    </div>

</form>
