<div class="modal fade" id="convertModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.enquiries.convert', $item->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Convert to Lead: {{ $item->customer_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-1">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to" class="form-control" required>
                                <option value="">Select User</option>
                                @foreach($visitUsers as $user)
                                    <option value="{{ $user->id }}" {{ Auth::id() == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-1">
                            <label class="form-label">Visit Date</label>
                            <input type="date" name="visit_date" class="form-control" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="alert alert-info py-1 px-2 small">
                        Converting this enquiry will create a new Lead in the 'Site Visit' stage and schedule a visit.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Convert</button>
                </div>
            </form>
        </div>
    </div>
</div>
