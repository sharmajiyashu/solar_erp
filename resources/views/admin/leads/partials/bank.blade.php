<div class="d-flex justify-content-between mb-2">
    <h5>Bank Documents</h5>

    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBankDocumentModal">
        + Upload Document
    </button>
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

        @forelse($lead->bankDocuments as $key => $doc)
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

                                    <form action="{{ route('admin.bank-documents.destroy', $doc->id) }}" method="POST">

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
                    No Bank Documents Uploaded
                </td>
            </tr>
        @endforelse

    </tbody>
</table>




<!-- ================= Upload Modal ================= -->

<div class="modal fade" id="addBankDocumentModal">
    <div class="modal-dialog">

        <form action="{{ route('admin.bank-documents.store', $lead->id) }}" method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Upload Bank Document</h5>
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
