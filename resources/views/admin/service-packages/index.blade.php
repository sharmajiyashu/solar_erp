@extends('admin.layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>

        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row mb-2">
                <div class="content-header-left col-md-8 col-12 d-flex align-items-center">
                    <div class="breadcrumbs-top">
                        <h2 class="content-header-title float-start mb-0">Service Packages</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Service Package List</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary d-flex align-items-center ms-auto" data-bs-toggle="modal" data-bs-target="#addPackageModal" style="border-radius: 8px; box-shadow: 0 4px 14px 0 rgba(113, 187, 178, 0.39);">
                        <i data-feather="plus" class="me-50"></i>
                        <span>Add New Package</span>
                    </button>
                </div>
            </div>

            <div class="content-body">
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0" style="border-radius: 12px; box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);">
                                <div class="card-header border-bottom py-2">
                                    <h4 class="card-title" style="font-weight: 600;">Service Offerings</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead style="background-color: #f8f9fa;">
                                                <tr>
                                                    <th class="ps-2 py-1" style="width: 50px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">#</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Details</th>
                                                    <th class="py-1 text-end" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Price (₹)</th>
                                                    <th class="py-1 text-center" style="width: 100px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Status</th>
                                                    <th class="py-1 text-center pe-2" style="width: 120px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($packages as $key => $package)
                                                    <tr style="transition: all 0.2s ease;">
                                                        <td class="ps-2 py-1 align-middle text-muted small fw-bold">
                                                            {{ ($packages->currentPage() - 1) * $packages->perPage() + $loop->iteration }}
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="fw-bolder text-dark" style="font-size: 0.95rem;">{{ $package->name }}</span><br>
                                                            <small class="text-muted">{{ \Str::limit($package->description, 40) }}</small>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="badge badge-light-primary text-uppercase">{{ str_replace('_', ' ', $package->package_type) }}</span>
                                                            <span class="badge badge-light-info"> Every {{ str_replace('_days', ' Days', $package->frequency) }}</span>
                                                            <span class="badge badge-light-warning"> {{ str_replace('_', ' ', $package->duration_type) }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle text-end fw-bolder text-dark" style="font-size: 1rem;">
                                                            ₹{{ number_format($package->price, 2) }}
                                                        </td>
                                                        <td class="py-1 align-middle text-center">
                                                            <div class="form-check form-switch d-flex justify-content-center">
                                                                <input type="checkbox" class="form-check-input status-toggle" 
                                                                    data-id="{{ $package->id }}" {{ $package->status ? 'checked' : '' }} role="switch" style="cursor: pointer;">
                                                            </div>
                                                        </td>
                                                        <td class="py-1 align-middle text-center pe-2">
                                                            <div class="d-flex justify-content-center">
                                                                <button class="btn btn-icon btn-flat-primary btn-sm edit-package me-25" data-id="{{ $package->id }}" title="Edit">
                                                                    <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                                                </button>
                                                                <button class="btn btn-icon btn-flat-danger btn-sm delete-package" data-id="{{ $package->id }}" title="Delete">
                                                                    <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-5">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <i data-feather="package" class="text-muted mb-1" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                                                                <p class="text-muted font-medium-2">No service packages found.</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @if ($packages->hasPages())
                                        <div class="card-footer py-1">
                                            {{ $packages->links('vendor.pagination.bootstrap-5') }}
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

    <!-- Add Package Modal -->
    <div class="modal fade" id="addPackageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px 0 rgba(0,0,0,0.1);">
                <div class="modal-header bg-transparent border-0 pb-0">
                    <h5 class="modal-title fw-bolder">Add New Service Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addPackageForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Package Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g., Solar Maintenance Basic" required />
                                    <span class="text-danger small name_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Price (₹)</label>
                                    <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" required />
                                    <span class="text-danger small price_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Package Type</label>
                                    <select name="package_type" class="form-select" required>
                                        <option value="subscription">Subscription</option>
                                        <option value="one_time">One Time Project</option>
                                    </select>
                                    <span class="text-danger small package_type_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Service Interval</label>
                                    <select name="frequency" class="form-select" required>
                                        <option value="7_days">Every 7 Days</option>
                                        <option value="15_days">Every 15 Days</option>
                                        <option value="30_days">Monthly</option>
                                    </select>
                                    <span class="text-danger small frequency_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Duration</label>
                                    <select name="duration_type" class="form-select" required>
                                        <option value="monthly">1 Month</option>
                                        <option value="3_months">3 Months</option>
                                        <option value="6_months">6 Months</option>
                                        <option value="9_months">9 Months</option>
                                        <option value="12_months">12 Months</option>
                                    </select>
                                    <span class="text-danger small duration_type_error"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="Brief overview of the package"></textarea>
                                    <span class="text-danger small description_error"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold d-flex justify-content-between">
                                    Features
                                    <button type="button" class="btn btn-sm btn-outline-primary add-feature-row">
                                        <i data-feather="plus" style="width: 12px; height: 12px;"></i> Add Feature
                                    </button>
                                </label>
                                <div id="add-features-container" class="mt-1">
                                    <div class="input-group mb-50 feature-row">
                                        <input type="text" name="features[]" class="form-control form-control-sm" placeholder="e.g., 2 Preventive maintenance visits" />
                                        <button class="btn btn-outline-danger btn-sm remove-feature-row" type="button"><i data-feather="x"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-3 shadow-none submit-btn">
                            <span>Create Package</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Package Modal -->
    <div class="modal fade" id="editPackageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px 0 rgba(0,0,0,0.1);">
                <div class="modal-header bg-transparent border-0 pb-0">
                    <h5 class="modal-title fw-bolder">Edit Service Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPackageForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_package_id">
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Package Name</label>
                                    <input type="text" name="name" id="edit_name" class="form-control" required />
                                    <span class="text-danger small name_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Price (₹)</label>
                                    <input type="number" name="price" id="edit_price" class="form-control" step="0.01" required />
                                    <span class="text-danger small price_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Package Type</label>
                                    <select name="package_type" id="edit_package_type" class="form-select" required>
                                        <option value="subscription">Subscription</option>
                                        <option value="one_time">One Time Project</option>
                                    </select>
                                    <span class="text-danger small package_type_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Service Interval</label>
                                    <select name="frequency" id="edit_frequency" class="form-select" required>
                                        <option value="7_days">Every 7 Days</option>
                                        <option value="15_days">Every 15 Days</option>
                                        <option value="30_days">Monthly</option>
                                    </select>
                                    <span class="text-danger small frequency_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Duration</label>
                                    <select name="duration_type" id="edit_duration_type" class="form-select" required>
                                        <option value="monthly">1 Month</option>
                                        <option value="3_months">3 Months</option>
                                        <option value="6_months">6 Months</option>
                                        <option value="9_months">9 Months</option>
                                        <option value="12_months">12 Months</option>
                                    </select>
                                    <span class="text-danger small duration_type_error"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                                    <span class="text-danger small description_error"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold d-flex justify-content-between">
                                    Features
                                    <button type="button" class="btn btn-sm btn-outline-primary add-feature-row">
                                        <i data-feather="plus" style="width: 12px; height: 12px;"></i> Add Feature
                                    </button>
                                </label>
                                <div id="edit-features-container" class="mt-1">
                                    <!-- Features will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-3 shadow-none submit-btn">
                            <span>Update Package</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            function clearErrors(form) {
                form.find('.text-danger').text('');
            }

            function toggleLoader(form, state) {
                let btn = form.find('.submit-btn');
                if (state) {
                    btn.attr('disabled', true).html('<span class="spinner-border spinner-border-sm me-50"></span> Processing...');
                } else {
                    let text = form.attr('id') === 'addPackageForm' ? 'Create Package' : 'Update Package';
                    btn.attr('disabled', false).html('<span>' + text + '</span>');
                }
            }

            // Add Feature Row
            $(document).on('click', '.add-feature-row', function() {
                let container = $(this).closest('form').find('[id$="-features-container"]');
                container.append(`
                    <div class="input-group mb-50 feature-row">
                        <input type="text" name="features[]" class="form-control form-control-sm" placeholder="Enter feature..." />
                        <button class="btn btn-outline-danger btn-sm remove-feature-row" type="button"><i data-feather="x"></i></button>
                    </div>
                `);
                feather.replace();
            });

            // Remove Feature Row
            $(document).on('click', '.remove-feature-row', function() {
                $(this).closest('.feature-row').remove();
            });

            // Add Package
            $('#addPackageForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                clearErrors(form);
                toggleLoader(form, true);

                $.ajax({
                    url: "{{ route('admin.service-packages.store') }}",
                    method: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        toggleLoader(form, false);
                        $('#addPackageModal').modal('hide');
                        Toastify({
                            text: response.success,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        }).showToast();
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function(xhr) {
                        toggleLoader(form, false);
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                form.find('.' + key + '_error').text(value[0]);
                            });
                        }
                    }
                });
            });

            // Edit Package
            $(document).on('click', '.edit-package', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ url('admin/service-packages') }}/" + id + "/edit",
                    method: "GET",
                    success: function(data) {
                        $('#edit_package_id').val(data.id);
                        $('#edit_name').val(data.name);
                        $('#edit_price').val(data.price);
                        $('#edit_description').val(data.description);
                        $('#edit_package_type').val(data.package_type);
                        $('#edit_frequency').val(data.frequency);
                        $('#edit_duration_type').val(data.duration_type);
                        
                        // Clear and load features
                        let container = $('#edit-features-container');
                        container.empty();
                        if (data.features && Array.isArray(data.features)) {
                            data.features.forEach(feature => {
                                container.append(`
                                    <div class="input-group mb-50 feature-row">
                                        <input type="text" name="features[]" class="form-control form-control-sm" value="${feature}" />
                                        <button class="btn btn-outline-danger btn-sm remove-feature-row" type="button"><i data-feather="x"></i></button>
                                    </div>
                                `);
                            });
                        }
                        feather.replace();
                        clearErrors($('#editPackageForm'));
                        $('#editPackageModal').modal('show');
                    }
                });
            });

            // Update Package
            $('#editPackageForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let id = $('#edit_package_id').val();
                clearErrors(form);
                toggleLoader(form, true);

                $.ajax({
                    url: "{{ url('admin/service-packages') }}/" + id,
                    method: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        toggleLoader(form, false);
                        $('#editPackageModal').modal('hide');
                        Toastify({
                            text: response.success,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        }).showToast();
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function(xhr) {
                        toggleLoader(form, false);
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                form.find('.' + key + '_error').text(value[0]);
                            });
                        }
                    }
                });
            });

            // Delete Package
            $(document).on('click', '.delete-package', function() {
                if (confirm('Are you sure you want to delete this package?')) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('admin/service-packages') }}/" + id,
                        method: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            Toastify({
                                text: response.success,
                                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                            }).showToast();
                            setTimeout(() => location.reload(), 1000);
                        }
                    });
                }
            });

            // Status Toggle
            $(document).on('change', '.status-toggle', function() {
                let id = $(this).data('id');
                let status = $(this).prop('checked') ? 1 : 0;
                $.ajax({
                    url: "{{ url('admin/service-packages') }}/" + id + "/status",
                    method: "POST",
                    data: { _token: "{{ csrf_token() }}", status: status },
                    success: function(response) {
                        Toastify({
                            text: response.success,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        }).showToast();
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
