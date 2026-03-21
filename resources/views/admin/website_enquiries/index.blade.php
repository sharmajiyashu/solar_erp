@extends('admin.layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>

        <div class="content-wrapper container-xxl p-0">

            <!-- Header -->
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Website Enquiries</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Website Enquiry List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">

                <section>
                    <div class="row">
                        <div class="col-12">
                            
                            <!-- Custom Tabs -->
                            <ul class="nav nav-tabs" id="enquiryTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status', 'pending') == 'pending' ? 'active font-weight-bold text-primary' : '' }}" 
                                       href="{{ route('admin.website-enquiries.index', ['status' => 'pending', 'type' => request('type')]) }}">
                                        Pending List
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status') == 'replied' ? 'active font-weight-bold text-primary' : '' }}" 
                                       href="{{ route('admin.website-enquiries.index', ['status' => 'replied', 'type' => request('type')]) }}">
                                        Replied
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status') == 'closed' ? 'active font-weight-bold text-primary' : '' }}" 
                                       href="{{ route('admin.website-enquiries.index', ['status' => 'closed', 'type' => request('type')]) }}">
                                        Closed Enquiries
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status') == 'all' ? 'active font-weight-bold text-primary' : '' }}" 
                                       href="{{ route('admin.website-enquiries.index', ['status' => 'all', 'type' => request('type')]) }}">
                                        All List
                                    </a>
                                </li>
                            </ul>

                            <div class="card card-company-table mt-1">

                                <!-- Filters and Export -->
                                <div class="card-header border-bottom">
                                    <div class="d-flex align-items-center w-100 justify-content-between">
                                        <form action="{{ route('admin.website-enquiries.index') }}" method="GET" class="d-flex align-items-center">
                                            <input type="hidden" name="status" value="{{ request('status', 'pending') }}">
                                            <select name="type" class="form-control me-1" onchange="this.form.submit()" style="width: 200px;">
                                                <option value="">All Enquiry Types</option>
                                                <option value="contact" {{ request('type') == 'contact' ? 'selected' : '' }}>Contact Us</option>
                                                <option value="quotation" {{ request('type') == 'quotation' ? 'selected' : '' }}>Quotation</option>
                                            </select>
                                            @if(request('type') || request('status'))
                                                <a href="{{ route('admin.website-enquiries.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                                            @endif
                                        </form>
                                        <div>
                                            <span class="badge bg-light-primary text-primary">Total: {{ $enquiries->total() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Customer Name</th>
                                                <th>Contact Details</th>
                                                <th>Subject / Type</th>
                                                <th width="150">Current Status</th>
                                                <th>Submitted At</th>
                                                <th width="150">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($enquiries as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration + ($enquiries->currentPage() - 1) * $enquiries->perPage() }}</td>

                                                    <td>
                                                        <div class="fw-bolder fs-6">{{ $item->name }}</div>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span><i data-feather="mail" class="me-1" style="width: 14px;"></i>{{ $item->email }}</span>
                                                            @if($item->mobile)
                                                                <span class="text-muted small"><i data-feather="phone" class="me-1" style="width: 14px;"></i>{{ $item->mobile }}</span>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span title="{{ $item->subject }}">{{ Str::limit($item->subject ?? 'N/A', 25) }}</span>
                                                            <div>
                                                                @if($item->type == 'quotation')
                                                                    <span class="badge badge-light-primary">Quotation</span>
                                                                @else
                                                                    <span class="badge badge-light-info">Contact Us</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <select class="form-select status-select border-0 bg-transparent text-primary fw-bold" 
                                                                data-id="{{ $item->id }}" style="cursor: pointer;">
                                                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="replied" {{ $item->status == 'replied' ? 'selected' : '' }}>Replied</option>
                                                            <option value="closed" {{ $item->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <div class="small">{{ $item->created_at->format('d M, Y') }}</div>
                                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $item->created_at->format('h:i A') }}</div>
                                                    </td>

                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-flat-primary view-btn" 
                                                                    data-name="{{ $item->name }}"
                                                                    data-email="{{ $item->email }}"
                                                                    data-mobile="{{ $item->mobile }}"
                                                                    data-subject="{{ $item->subject }}"
                                                                    data-message="{{ $item->message }}"
                                                                    data-type="{{ ucfirst($item->type) }}"
                                                                    data-status="{{ ucfirst($item->status) }}"
                                                                    data-date="{{ $item->created_at->format('d M, Y h:i A') }}"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#viewModal">
                                                                <i data-feather="eye" class="me-50"></i> View
                                                            </button>
                                                            
                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-sm btn-flat-secondary dropdown-toggle dropdown-empty-hide" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item text-danger delete-btn" 
                                                                       href="javascript:void(0);" 
                                                                       data-id="{{ $item->id }}"
                                                                       data-bs-toggle="modal" 
                                                                       data-bs-target="#deleteModal">
                                                                        <i data-feather="trash-2" class="me-50"></i> Delete
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-2">
                                                        <div class="p-2 border rounded bg-light">
                                                            <i data-feather="inbox" class="mb-1" style="width: 40px; height: 40px; color: #ccc;"></i>
                                                            <p class="text-muted">No website enquiries found in this category.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    @if($enquiries->hasPages())
                                        <div class="card-footer py-1">
                                            {{ $enquiries->appends(request()->query())->links() }}
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Enquiry Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row mb-2">
                        <div class="col-md-6 border-bottom pb-1 mb-1 mb-md-0 border-md-0">
                            <label class="text-muted small text-uppercase fw-bold">Customer Details</label>
                            <h4 id="view-name" class="mb-0 text-primary"></h4>
                            <div class="mt-1">
                                <p class="mb-0"><strong>Email:</strong> <span id="view-email"></span></p>
                                <p class="mb-0"><strong>Mobile:</strong> <span id="view-mobile"></span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold">Submission Info</label>
                            <div class="mt-1">
                                <p class="mb-1"><strong>Type:</strong> <span class="badge badge-light-info" id="view-type"></span></p>
                                <p class="mb-1"><strong>Status:</strong> <span class="badge" id="view-status"></span></p>
                                <p class="mb-0 text-muted small"><i data-feather="calendar" class="me-25" style="width: 14px;"></i> <span id="view-date"></span></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-2">
                        <label class="text-muted small text-uppercase fw-bold">Subject & Message</label>
                        <h5 id="view-subject" class="mt-1 border-start border-primary border-4 ps-1"></h5>
                        <div class="bg-light p-2 mt-2 rounded border" style="min-height: 150px; white-space: pre-wrap;" id="view-message"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2 text-center">
                    <i data-feather="alert-triangle" class="text-danger mb-1" style="width: 50px; height: 50px;"></i>
                    <p class="fs-5">Are you sure you want to delete this enquiry?</p>
                    <p class="text-muted small">This action cannot be undone and will permanently remove the record.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="delete-form" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete It</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // View Details
            $('.view-btn').on('click', function() {
                const data = $(this).data();
                $('#view-name').text(data.name);
                $('#view-email').text(data.email);
                $('#view-mobile').text(data.mobile || 'Not Provided');
                $('#view-subject').text(data.subject || 'No Subject');
                $('#view-message').text(data.message);
                $('#view-type').text(data.type);
                $('#view-date').text(data.date);
                
                const status = data.status;
                const statusBadge = $('#view-status');
                statusBadge.text(status);
                statusBadge.removeClass('bg-warning bg-success bg-secondary');
                if (status === 'Pending') statusBadge.addClass('bg-warning');
                else if (status === 'Replied') statusBadge.addClass('bg-success');
                else statusBadge.addClass('bg-secondary');
                
                setTimeout(() => {
                    if (typeof feather !== 'undefined') feather.replace();
                }, 100);
            });

            // Delete Confirmation
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                let url = "{{ route('admin.website-enquiries.destroy', ':id') }}";
                url = url.replace(':id', id);
                $('#delete-form').attr('action', url);
            });

            // Update Status
            $('.status-select').on('change', function() {
                const id = $(this).data('id');
                const status = $(this).val();
                let url = "{{ route('admin.website-enquiries.updateStatus', ['id' => ':id']) }}";
                url = url.replace(':id', id);
                
                $(this).addClass('opacity-50');
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status
                    },
                    success: function(response) {
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)",
                            }
                        }).showToast();
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function() {
                        alert('Something went wrong!');
                        location.reload();
                    }
                });
            });
        });
    </script>
@endsection
