<form action="{{ route('admin.installation.store', $lead->id) }}" method="POST" enctype="multipart/form-data">

    @csrf

    <div class="modal-body">

        <div class="row">

            <!-- Installation Date -->
            <div class="col-md-6 mb-1">
                <label>Installation Date</label>

                <input type="date" name="installation_date" value="{{ $lead->installation->installation_date ?? '' }}"
                    class="form-control">
            </div>

            <div class="col-md-6 mb-1">
                <label>Attachments</label>

                <input type="file" name="attachments[]" class="form-control" multiple>
            </div>


            <!-- Notes -->
            <div class="col-md-12 mb-1">
                <label>Notes</label>

                <textarea name="notes" class="form-control">{{ $lead->installation->notes ?? '' }}</textarea>

            </div>

            <!-- Tracking Checkboxes -->
            <div class="col-md-12 mb-1">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="installation_done" id="installation_done" {{ ($lead->installation->installation_done ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="installation_done">Installation Done</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="net_metering_pending" id="net_metering_pending" {{ ($lead->installation->net_metering_pending ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="net_metering_pending">Net Metering Pending</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="net_metering_done" id="net_metering_done" {{ ($lead->installation->net_metering_done ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="net_metering_done">Net Metering Done</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="second_tier_payment_received" id="second_tier_payment_received" {{ ($lead->installation->second_tier_payment_received ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="second_tier_payment_received">2nd Tier Payment Received</label>
                        </div>
                    </div>
                </div>
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


@if ($lead->installation && $lead->installation->attachments->count())

    <div class="mt-3">

        <h6>Attachments</h6>

        <table class="table table-bordered table-sm">

            <thead class="table-light">
                <tr>
                    <th width="60">#</th>
                    <th>File</th>
                    <th width="180">Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($lead->installation->attachments as $key => $file)
                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>{{ basename($file->file) }}</td>

                        <td>

                            <!-- VIEW BUTTON -->
                            <a href="{{ url('public/' . $file->file) }}" target="_blank" class="btn btn-sm btn-primary">
                                View
                            </a>

                            <!-- DELETE BUTTON -->
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteAttachmentModal{{ $file->id }}">
                                Delete
                            </button>

                        </td>

                    </tr>


                    <!-- DELETE MODAL -->
                    <div class="modal fade" id="deleteAttachmentModal{{ $file->id }}">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5>Confirm Delete</h5>

                                    <button class="btn-close" data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <div class="modal-body">
                                    Are you sure you want to delete this attachment?
                                </div>

                                <div class="modal-footer">

                                    <form action="{{ route('admin.installation.attachment.delete', $file->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger">
                                            Yes Delete
                                        </button>

                                    </form>

                                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancel
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>
                @endforeach

            </tbody>

        </table>

    </div>

@endif
