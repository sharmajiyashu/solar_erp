<div class="d-flex justify-content-between mb-2">
    <h5>Documents</h5>

    <div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
            + Upload Document
        </button>
    </div>
</div>

<div class="card mb-3 border-primary">
    <div class="card-body">
        <h6 class="card-title text-primary">Stage Tracking</h6>
        <form action="{{ route('admin.document-tracking.tracking', $lead->id) }}" method="POST">
            @csrf
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="first_payment_received" id="first_payment_received" {{ $lead->first_payment_received ? 'checked' : '' }}>
                        <label class="form-check-label" for="first_payment_received">First Payment Received</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="is_document_done" id="is_document_done" {{ $lead->is_document_done ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_document_done">Document Done & Received</label>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                </div>
            </div>
            @if($lead->first_payment_received && $lead->is_document_done && $lead->stage == 'document')
                <div class="mt-2 text-success small">
                    <i class="fas fa-info-circle"></i> Both completed. Ready to move to Backend.
                </div>
            @endif
        </form>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Document Type</th>
            <th>File</th>
            <th>Uploaded At</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

        @forelse($lead->documents as $key => $doc)
            <tr>

                <td>{{ $key + 1 }}</td>

                <td>{{ $doc->document_type }}</td>

                <td>
                    <a href="{{ url('public/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                        View File
                    </a>
                </td>

                <td>
                    {{ $doc->created_at->format('d-m-Y') }}
                </td>

                <td>

                    <!-- Delete Document -->
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteDocModal{{ $doc->id }}">
                        Delete
                    </button>

                    <div class="modal fade" id="deleteDocModal{{ $doc->id }}">
                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5>Delete Document</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <h5>Are you sure you want to delete this document?</h5>

                                    <p class="text-danger">
                                        {{ $doc->document_type }}
                                    </p>
                                </div>

                                <div class="modal-footer">

                                    <form action="{{ route('admin.document-tracking.destroy', $doc->id) }}" method="POST">

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

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="6" class="text-center">
                    No Documents Uploaded
                </td>
            </tr>
        @endforelse

    </tbody>
</table>




<!-- ================= Upload Modal ================= -->

<div class="modal fade" id="addDocumentModal">
    <div class="modal-dialog">

        <form action="{{ route('admin.document-tracking.store', $lead->id) }}" method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Upload Document</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Document Type</label>

                        <select name="document_type" class="form-control" required>

                            <option value="">Select Document</option>
                            <option value="Pan Card">Pan Card</option>
                            <option value="Aadhar Card">Aadhar Card</option>
                            <option value="Bank Statement">Bank Statement</option>
                            <option value="Salary Slip">Salary Slip</option>
                            <option value="Electricity Bill">Electricity Bill</option>
                            <option value="Roof Photo">Roof Photo</option>
                            <option value="Other">Other</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Upload File</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.png" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button class="btn btn-success">
                        Upload
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>
