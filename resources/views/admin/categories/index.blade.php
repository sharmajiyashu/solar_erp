@extends('admin.layouts.app')

@section('content')

    



    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>

        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row mb-2">
                <div class="content-header-left col-md-8 col-12 d-flex align-items-center">
                  
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary d-flex align-items-center ms-auto" data-bs-toggle="modal" data-bs-target="#addCategoryModal" style="border-radius: 8px; box-shadow: 0 4px 14px 0 rgba(113, 187, 178, 0.39);">
                        <i data-feather="plus" class="me-50"></i>
                        <span>Add New Category</span>
                    </button>
                </div>
            </div>

            <div class="content-body">
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0" style="border-radius: 12px; box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);">
                                <div class="card-header border-bottom py-2">
                                    <h4 class="card-title" style="font-weight: 600;">Managed Categories</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead style="background-color: #f8f9fa;">
                                                <tr>
                                                    <th class="ps-2 py-1" style="width: 80px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">#</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Category Name</th>
                                                    <th class="py-1 text-center" style="width: 150px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Status</th>
                                                    <th class="py-1 text-center pe-2" style="width: 150px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($categories as $key => $category)
                                                    <tr style="transition: all 0.2s ease;">
                                                        <td class="ps-2 py-1 align-middle">
                                                            <span class="text-muted fw-bold">{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <div class="d-flex flex-column">
                                                                <span class="fw-bolder" style="color: #444; font-size: 0.95rem;">{{ $category->name }}</span>
                                                                <small class="text-muted">ID: #{{ $category->id }}</small>
                                                            </div>
                                                        </td>
                                                        <td class="py-1 align-middle text-center">
                                                            <div class="form-check form-switch d-flex justify-content-center">
                                                                <input type="checkbox" class="form-check-input status-toggle custom-switch" 
                                                                    data-id="{{ $category->id }}" {{ $category->status ? 'checked' : '' }} role="switch" style="cursor: pointer; width: 40px; height: 20px;">
                                                            </div>
                                                        </td>
                                                        <td class="py-1 align-middle text-center pe-2">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <button class="btn btn-icon btn-flat-primary edit-category me-50" data-id="{{ $category->id }}" title="Edit" style="border-radius: 6px;">
                                                                    <i data-feather="edit" style="width: 16px; height: 16px;"></i>
                                                                </button>
                                                                <button class="btn btn-icon btn-flat-danger delete-category" data-id="{{ $category->id }}" title="Delete" style="border-radius: 6px;">
                                                                    <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-5">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <i data-feather="alert-circle" class="text-muted mb-1" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                                                                <p class="text-muted font-medium-2">No categories found in the database.</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="px-2 py-1 border-top d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} entries
                                        </div>
                                        <div>
                                            @include('admin._pagination', ['data' => $categories])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header bg-transparent border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 pb-4">
                    <div class="text-center mb-2">
                        <h1 class="mb-50" style="font-weight: 700; color: #333;">Add New Category</h1>
                        <p class="text-muted">Expand your product range with a new category</p>
                    </div>
                    <form id="addCategoryForm" class="row gy-2 pt-1">
                        @csrf
                        <div class="col-12">
                            <label class="form-label fw-bold" for="category_name" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Category Name</label>
                            <input type="text" id="category_name" name="name" class="form-control" placeholder="Enter Category Name" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #d8d6de;" />
                            <span class="text-danger error-text name_error small"></span>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary px-3 submit-btn" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 14px 0 rgba(113, 187, 178, 0.4);">
                                <span class="spinner-border spinner-border-sm d-none me-50"></span>
                                Create Category
                            </button>
                            <button type="reset" class="btn btn-outline-secondary ms-1" data-bs-dismiss="modal" aria-label="Close" style="border-radius: 8px;">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header bg-transparent border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 pb-4">
                    <div class="text-center mb-2">
                        <h1 class="mb-50" style="font-weight: 700; color: #333;">Edit Category</h1>
                        <p class="text-muted">Modify existing category details</p>
                    </div>
                    <form id="editCategoryForm" class="row gy-2 pt-1">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_category_id" name="id">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="edit_category_name" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Category Name</label>
                            <input type="text" id="edit_category_name" name="name" class="form-control" placeholder="Enter Category Name" style="padding: 0.75rem; border-radius: 8px; border: 1px solid #d8d6de;" />
                            <span class="text-danger error-text name_error small"></span>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary px-3 submit-btn" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 14px 0 rgba(113, 187, 178, 0.4);">
                                <span class="spinner-border spinner-border-sm d-none me-50"></span>
                                Update Category
                            </button>
                            <button type="reset" class="btn btn-outline-secondary ms-1" data-bs-dismiss="modal" aria-label="Close" style="border-radius: 8px;">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Helper function to show/hide loader
            function toggleLoader(form, show) {
                let btn = form.find('.submit-btn');
                let spinner = btn.find('.spinner-border');
                if (show) {
                    btn.attr('disabled', true);
                    spinner.removeClass('d-none');
                } else {
                    btn.attr('disabled', false);
                    spinner.addClass('d-none');
                }
            }

            // Clear validation errors
            function clearErrors(form) {
                form.find('.error-text').text('');
            }

            // Add Category
            $('#addCategoryForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                clearErrors(form);
                toggleLoader(form, true);

                $.ajax({
                    url: "{{ route('admin.categories.store') }}",
                    method: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        toggleLoader(form, false);
                        $('#addCategoryModal').modal('hide');
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
                        } else {
                            alert('Something went wrong!');
                        }
                    }
                });
            });

            // Edit Category (Auto-fill)
            $(document).on('click', '.edit-category', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ url('admin/categories') }}/" + id + "/edit",
                    method: "GET",
                    success: function(data) {
                        $('#edit_category_id').val(data.id);
                        $('#edit_category_name').val(data.name);
                        clearErrors($('#editCategoryForm'));
                        $('#editCategoryModal').modal('show');
                    }
                });
            });

            // Update Category
            $('#editCategoryForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let id = $('#edit_category_id').val();
                clearErrors(form);
                toggleLoader(form, true);

                $.ajax({
                    url: "{{ url('admin/categories') }}/" + id,
                    method: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        toggleLoader(form, false);
                        $('#editCategoryModal').modal('hide');
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

            // Delete Category
            $(document).on('click', '.delete-category', function() {
                if (confirm('Are you sure? This will delete the category.')) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('admin/categories') }}/" + id,
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
                    url: "{{ url('admin/categories') }}/" + id + "/status",
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
