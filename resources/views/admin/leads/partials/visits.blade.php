@can('site_visits schedule')
<button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addVisitModal">
    + Create Visit
</button>
@endcan

<table class="table table-bordered">

    <thead>
        <tr>
            <th>#</th>
            <th>Visit Date</th>
            <th>User</th>
            <th>Status</th>
            <th>Notes</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

        @forelse($lead->visits as $key=>$visit)
            <tr>
                <td>{{ $key + 1 }}</td>

                <td>
                    {{ \Carbon\Carbon::parse($visit->visit_date)->format('d-m-Y') }}
                </td>

                <td>{{ $visit->user->name ?? '-' }}</td>

                <td>
                    @if ($visit->status == 'completed')
                        <span class="badge bg-success">Completed</span>
                    @elseif($visit->status == 'rescheduled')
                        <span class="badge bg-info">Rescheduled</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </td>

                <td>{{ $visit->notes }}</td>

                <td>
                    @can('site_visits edit')
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#editVisitModal{{ $visit->id }}">
                        Edit
                    </button>
                    @endcan


                    <div class="modal fade" id="editVisitModal{{ $visit->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">

                            <div class="modal-content">

                                <form action="{{ route('admin.leads.updateVisit', $visit->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header bg-primary text-white">
                                        <h5>Edit Site Visit</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="row">

                                            <div class="col-md-4 mb-2">
                                                <label>Visit Date</label>
                                                <input type="date" name="visit_date" class="form-control"
                                                    value="{{ $visit->visit_date }}">
                                            </div>

                                            <div class="col-md-4 mb-2">
                                                <label>Status</label>

                                                <select name="status" class="form-control">

                                                    <option value="pending"
                                                        {{ $visit->status == 'pending' ? 'selected' : '' }}>
                                                        Pending
                                                    </option>

                                                    <option value="completed"
                                                        {{ $visit->status == 'completed' ? 'selected' : '' }}>
                                                        Completed
                                                    </option>

                                                    <option value="rescheduled"
                                                        {{ $visit->status == 'rescheduled' ? 'selected' : '' }}>
                                                        Rescheduled
                                                    </option>

                                                </select>
                                            </div>

                                            <div class="col-md-4 mb-2">
                                                <label>Assign User</label>

                                                <select name="user_id" class="form-control">

                                                    @foreach ($visitUsers as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ $visit->user_id == $user->id ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>

                                            <div class="col-12 mb-2">
                                                <label>Notes</label>
                                                <textarea name="notes" class="form-control">{{ $visit->notes }}</textarea>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <button class="btn btn-success">
                                            Update Visit
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="5" class="text-center">No Visits Found</td>
            </tr>
        @endforelse

    </tbody>

</table>




<div class="modal fade" id="addVisitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form action="{{ route('admin.leads.storeVisit', $lead->id) }}" method="POST">
                @csrf

                <div class="modal-header bg-primary text-white">
                    <h5>Create Site Visit</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4 mb-2">
                            <label>Assign User</label>
                            <select name="user_id" class="form-control" required>
                                <option value="">Select User</option>

                                @foreach ($visitUsers as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Visit Date</label>
                            <input type="date" name="visit_date" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="rescheduled">Rescheduled</option>
                            </select>
                        </div>

                        <div class="col-12 mb-2">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button class="btn btn-success">
                        Save Visit
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
