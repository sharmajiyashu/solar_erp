<div class="d-flex justify-content-between mb-2">
    <h5>Documents</h5>

    @can('document_management create')
        <div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                + Upload Document
            </button>
        </div>
    @endcan
</div>

@can('document_management create')
    @if(!($is_past_stage ?? false))
    <div class="card mb-3 border-primary">
        <div class="card-body">
            <h6 class="card-title text-primary">Stage Tracking</h6>
            <form action="{{ route('admin.document-tracking.tracking', $lead->id) }}" method="POST">
                @csrf
                <div class="row align-items-center g-1">
                    <div class="col-md-2">
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="token_received" id="token_received" {{ $lead->token_received ? 'checked' : '' }}>
                            <label class="form-check-label text-nowrap" for="token_received">Token Amount Received</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" name="token_amount" class="form-control" placeholder="Amount" value="{{ $lead->token_amount }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="is_document_done" id="is_document_done" {{ $lead->is_document_done ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_document_done">Docs Done</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="lead_type" id="lead_type_select" class="form-select form-select-sm">
                            <option value="cash" {{ $lead->lead_type == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="loan" {{ $lead->lead_type == 'loan' ? 'selected' : '' }}>Loan</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Update</button>
                    </div>
                </div>
            </form>

            <div class="mt-2 pt-1 border-top">
                <h6 class="small fw-bold text-uppercase text-muted mb-1">Payment Summary</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Payment Stage</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">Token Amount</td>
                                <td class="text-center">
                                    @if($lead->token_received)
                                        <span class="badge bg-light-success text-success">Received</span>
                                    @else
                                        <span class="badge bg-light-warning text-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-primary">₹{{ number_format($lead->token_amount ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted small">First Tranche</td>
                                <td class="text-center">
                                    @if($lead->first_payment_received)
                                        <span class="badge bg-light-success text-success">Received</span>
                                    @else
                                        <span class="badge bg-light-secondary text-secondary">Not Started</span>
                                    @endif
                                </td>
                                <td class="text-end text-muted small">₹{{ number_format($lead->first_tranche_amount ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="card mb-3 border-primary">
            <div class="card-body">
                <h6 class="card-title text-primary">Stage Tracking (View Only)</h6>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <p class="mb-0 text-muted small">Token Amount</p>
                        <h6 class="mb-0 fw-bold">{{ $lead->token_received ? 'Received (₹'.number_format($lead->token_amount, 2).')' : 'Pending' }}</h6>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0 text-muted small">Document Status</p>
                        <h6 class="mb-0 fw-bold">{{ $lead->is_document_done ? 'Done & Received' : 'Pending' }}</h6>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-0 text-muted small">Lead Type</p>
                        <h6 class="mb-0 fw-bold">{{ ucfirst($lead->lead_type) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="card mb-3 border-primary">
        <div class="card-body">
            <h6 class="card-title text-primary">Stage Tracking</h6>
            <div class="row align-items-center">
                <div class="col-md-4">
                    <p class="mb-0 text-muted small">Token Amount</p>
                    <h6 class="mb-0 fw-bold">{{ $lead->token_received ? 'Received (₹'.number_format($lead->token_amount, 2).')' : 'Pending' }}</h6>
                </div>
                <div class="col-md-4">
                    <p class="mb-0 text-muted small">Document Status</p>
                    <h6 class="mb-0 fw-bold">{{ $lead->is_document_done ? 'Done & Received' : 'Pending' }}</h6>
                </div>
                <div class="col-md-4">
                    <p class="mb-0 text-muted small">Lead Type</p>
                    <h6 class="mb-0 fw-bold">{{ ucfirst($lead->lead_type) }}</h6>
                </div>
            </div>
        </div>
    </div>
@endcan

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
                    @can('document_management create')
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteDocModal{{ $doc->id }}">
                            Delete
                        </button>

                        <div class="modal fade" id="deleteDocModal{{ $doc->id }}">
                            <div class="modal-dialog">

                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5>Delete Document</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                    </div>

                                </div>

                            </div>
                        </div>
                    @endcan

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

@can('document_management create')
    <div class="modal fade" id="addDocumentModal">
        <div class="modal-dialog">

            <form action="{{ route('admin.document-tracking.store', $lead->id) }}" method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Upload Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
@endcan
@if($lead->stage == 'document')
    @can('document_management create')
        <div class="card border-primary mt-4">
            <div class="card-body text-center">
                <h6 class="text-primary mb-3">Backend Stage Transition</h6>
                <p>The lead will <strong>automatically</strong> move to the Backend stage once the following requirements are met:</p>
                
                <div class="d-flex justify-content-center gap-3 mb-3">
                    <div>
                        <span class="badge {{ $lead->token_received ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ $lead->token_received ? 'fa-check-circle' : 'fa-times-circle' }}"></i> 
                            Token Amount Received {{ $lead->token_amount ? '(₹'.$lead->token_amount.')' : '' }}
                        </span>
                    </div>
                    <div>
                        <span class="badge {{ $lead->is_document_done ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ $lead->is_document_done ? 'fa-check-circle' : 'fa-times-circle' }}"></i> 
                            Documents Received
                        </span>
                    </div>
                </div>

                @if(!$lead->token_received || !$lead->is_document_done)
                    <div class="mt-2 text-danger small">
                        <i class="fas fa-exclamation-triangle"></i> Please update the transaction and document status above to proceed.
                    </div>
                @else
                    <div class="mt-2 text-success small">
                        <i class="fas fa-check-double"></i> All requirements met. Lead is being processed.
                    </div>
                @endif
            </div>
        </div>
    @endcan
@endif
