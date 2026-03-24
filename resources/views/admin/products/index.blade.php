@extends('admin.layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>

        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row mb-2">
                <div class="content-header-left col-md-8 col-12 d-flex align-items-center">
                    <div class="breadcrumbs-top">
                            
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary d-flex align-items-center ms-auto" data-bs-toggle="modal" data-bs-target="#addProductModal" style="border-radius: 8px; box-shadow: 0 4px 14px 0 rgba(113, 187, 178, 0.39);">
                        <i data-feather="plus" class="me-50"></i>
                        <span>Add New Product</span>
                    </button>
                </div>
            </div>

            <div class="content-body">
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0" style="border-radius: 12px; box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);">
                                <div class="card-header border-bottom py-2">
                                    <h4 class="card-title" style="font-weight: 600;">Inventory Items</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead style="background-color: #f8f9fa;">
                                                <tr>
                                                    <th class="ps-2 py-1" style="width: 50px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">#</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Category</th>
                                                    <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Details</th>
                                                    <th class="py-1 text-end" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Pricing (₹)</th>
                                                    <th class="py-1 text-center" style="width: 100px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">3KW DCR</th>
                                                    <th class="py-1 text-center" style="width: 100px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Stock</th>
                                                    <th class="py-1 text-center" style="width: 100px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Status</th>
                                                    <th class="py-1 text-center pe-2" style="width: 150px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($products as $key => $product)
                                                    <tr style="transition: all 0.2s ease;">
                                                        <td class="ps-2 py-1 align-middle text-muted small fw-bold">
                                                            {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <span class="badge rounded-pill" style="background-color: rgba(113, 187, 178, 0.12); color: #71bbb2; font-weight: 600;">
                                                                {{ $product->category->name ?? 'Uncategorized' }}
                                                            </span>
                                                        </td>
                                                        <td class="py-1 align-middle">
                                                            <div class="d-flex flex-column">
                                                                <span class="fw-bolder" style="color: #444; font-size: 0.95rem;">{{ $product->subtype ?? 'N/A' }}</span>
                                                                <small class="text-muted">{{ $product->company ?? 'No Company' }}</small>
                                                            </div>
                                                        </td>
                                                        <td class="py-1 align-middle text-end">
                                                            <div class="d-flex flex-column align-items-end">
                                                                <span class="fw-bolder text-dark" style="font-size: 1rem;">₹{{ number_format($product->final_landing_with_gst, 2) }}</span>
                                                                <div style="font-size: 0.75rem;">
                                                                    <span class="text-muted">Base: ₹{{ number_format($product->total_landing_wo_gst, 2) }}</span>
                                                                    <span class="text-success ms-50">+{{ $product->gst_percentage }}% GST</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="py-1 align-middle text-center">
                                                            <span class="fw-bold text-secondary">{{ $product->three_kw_dcr_qnt ?? '-' }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle text-center">
                                                            <span class="badge badge-light-info current-stock-display-{{ $product->id }}" style="font-size: 0.9rem;">{{ $product->stock }}</span>
                                                        </td>
                                                        <td class="py-1 align-middle text-center">
                                                            <div class="form-check form-switch d-flex justify-content-center">
                                                                <input type="checkbox" class="form-check-input status-toggle" 
                                                                    data-id="{{ $product->id }}" {{ $product->status ? 'checked' : '' }} role="switch" style="cursor: pointer;">
                                                            </div>
                                                        </td>
                                                        <td class="py-1 align-middle text-center pe-2">
                                                            <div class="d-flex justify-content-center">
                                                                <button class="btn btn-icon btn-flat-success btn-sm manage-stock me-25" data-id="{{ $product->id }}" data-name="{{ $product->subtype }} ({{ $product->company }})" title="Manage Stock">
                                                                    <i data-feather="plus-circle" style="width: 14px; height: 14px;"></i>
                                                                </button>
                                                                <button class="btn btn-icon btn-flat-info btn-sm view-history me-25" data-id="{{ $product->id }}" title="Stock History">
                                                                    <i data-feather="list" style="width: 14px; height: 14px;"></i>
                                                                </button>
                                                                <button class="btn btn-icon btn-flat-primary btn-sm edit-product me-25" data-id="{{ $product->id }}" title="Edit">
                                                                    <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                                                </button>
                                                                <button class="btn btn-icon btn-flat-danger btn-sm delete-product" data-id="{{ $product->id }}" title="Delete">
                                                                    <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center py-5">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <i data-feather="box" class="text-muted mb-1" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                                                                <p class="text-muted font-medium-2">Your inventory is currently empty.</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="px-2 py-1 border-top d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                                        </div>
                                        <div>
                                            @include('admin._pagination', ['data' => $products])
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

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header bg-transparent border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 pb-5">
                    <div class="text-center mb-2">
                        <h1 class="mb-50" style="font-weight: 700; color: #333;">Add New Product</h1>
                        <p class="text-muted">Register a new item in your inventory</p>
                    </div>
                    <form id="addProductForm" class="row gy-2 pt-1">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Category</label>
                            <select name="category_id" class="form-select" style="padding: 0.75rem; border-radius: 8px;">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text category_id_error small"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Subtype</label>
                            <input type="text" name="subtype" class="form-control" placeholder="e.g. Mono Perc" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text subtype_error small"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Company</label>
                            <input type="text" name="company" class="form-control" placeholder="e.g. Adani" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text company_error small"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Total Landing (WO GST)</label>
                            <input type="number" step="0.01" name="total_landing_wo_gst" class="form-control calc-trigger" placeholder="0.00" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text total_landing_wo_gst_error small"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">GST %</label>
                            <input type="number" step="0.01" name="gst_percentage" class="form-control calc-trigger" placeholder="12" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text gst_percentage_error small"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Tax Amount</label>
                            <input type="number" step="0.01" name="tax_amount" class="form-control bg-light" readonly style="padding: 0.75rem; border-radius: 8px;" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Final (With GST)</label>
                            <input type="number" step="0.01" name="final_landing_with_gst" class="form-control bg-light fw-bold text-primary" readonly style="padding: 0.75rem; border-radius: 8px;" />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">3 KW DCR QNT</label>
                            <input type="text" name="three_kw_dcr_qnt" class="form-control" placeholder="e.g. 5" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text three_kw_dcr_qnt_error small"></span>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary px-3 submit-btn" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 14px 0 rgba(113, 187, 178, 0.4);">
                                <span class="spinner-border spinner-border-sm d-none me-50"></span>
                                Create Product
                            </button>
                            <button type="reset" class="btn btn-outline-secondary ms-1" data-bs-dismiss="modal" aria-label="Close" style="border-radius: 8px;">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header bg-transparent border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 pb-5">
                    <div class="text-center mb-2">
                        <h1 class="mb-50" style="font-weight: 700; color: #333;">Edit Product</h1>
                        <p class="text-muted">Update existing product information</p>
                    </div>
                    <form id="editProductForm" class="row gy-2 pt-1">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_product_id" name="id">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Category</label>
                            <select id="edit_category_id" name="category_id" class="form-select" style="padding: 0.75rem; border-radius: 8px;">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text category_id_error small"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Subtype</label>
                            <input type="text" id="edit_subtype" name="subtype" class="form-control" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text subtype_error small"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Company</label>
                            <input type="text" id="edit_company" name="company" class="form-control" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text company_error small"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Total Landing (WO GST)</label>
                            <input type="number" step="0.01" id="edit_total_landing_wo_gst" name="total_landing_wo_gst" class="form-control calc-trigger" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text total_landing_wo_gst_error small"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">GST %</label>
                            <input type="number" step="0.01" id="edit_gst_percentage" name="gst_percentage" class="form-control calc-trigger" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text gst_percentage_error small"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Tax Amount</label>
                            <input type="number" step="0.01" id="edit_tax_amount" name="tax_amount" class="form-control bg-light" readonly style="padding: 0.75rem; border-radius: 8px;" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">Final (With GST)</label>
                            <input type="number" step="0.01" id="edit_final_landing_with_gst" name="final_landing_with_gst" class="form-control bg-light fw-bold text-primary" readonly style="padding: 0.75rem; border-radius: 8px;" />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold" style="color: #5e5873; font-size: 0.85rem; text-transform: uppercase;">3 KW DCR QNT</label>
                            <input type="text" id="edit_three_kw_dcr_qnt" name="three_kw_dcr_qnt" class="form-control" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text three_kw_dcr_qnt_error small"></span>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary px-3 submit-btn" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 14px 0 rgba(113, 187, 178, 0.4);">
                                <span class="spinner-border spinner-border-sm d-none me-50"></span>
                                Update Product
                            </button>
                            <button type="reset" class="btn btn-outline-secondary ms-1" data-bs-dismiss="modal" aria-label="Close" style="border-radius: 8px;">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Stock Modal -->
    <div class="modal fade" id="manageStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header bg-transparent border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 pb-5">
                    <div class="text-center mb-2">
                        <h1 class="mb-50" style="font-weight: 700; color: #333;">Manage Stock</h1>
                        <p class="text-muted stock-product-name"></p>
                    </div>
                    <form id="manageStockForm" class="row gy-1 pt-75">
                        @csrf
                        <input type="hidden" name="product_id" id="stock_product_id">
                        <div class="col-12">
                            <label class="form-label fw-bold">Transaction Type</label>
                            <select name="type" class="form-select" style="padding: 0.75rem; border-radius: 8px;">
                                <option value="add">Add In (+)</option>
                                <option value="less">Less From (-)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="1" placeholder="Enter quantity" style="padding: 0.75rem; border-radius: 8px;" />
                            <span class="text-danger error-text quantity_error small"></span>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Reason</label>
                            <textarea name="reason" class="form-control" rows="2" placeholder="e.g. Purchase order #123 or Sale" style="padding: 0.75rem; border-radius: 8px;"></textarea>
                            <span class="text-danger error-text reason_error small"></span>
                        </div>
                        <div class="col-12 text-center mt-2">
                            <button type="submit" class="btn btn-success px-3 submit-btn" style="border-radius: 8px; font-weight: 600;">Update Stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock History Modal -->
    <div class="modal fade" id="stockHistoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header bg-transparent border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-3 pb-3">
                    <div class="text-center mb-2">
                        <h1 class="mb-50" style="font-weight: 700; color: #333;">Stock History</h1>
                        <p class="text-muted history-product-name"></p>
                    </div>
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Qty</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody id="stock-history-body">
                                <!-- History entries will be loaded here -->
                            </tbody>
                        </table>
                    </div>
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

            // Calculation Logic
            $(document).on('input', '.calc-trigger', function() {
                let form = $(this).closest('form');
                let woGst = parseFloat(form.find('input[name="total_landing_wo_gst"]').val()) || 0;
                let gstPer = parseFloat(form.find('input[name="gst_percentage"]').val()) || 0;
                
                let taxAmount = (woGst * gstPer) / 100;
                let finalPrice = woGst + taxAmount;

                form.find('input[name="tax_amount"]').val(taxAmount.toFixed(2));
                form.find('input[name="final_landing_with_gst"]').val(finalPrice.toFixed(2));
            });

            // Add Product
            $('#addProductForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                clearErrors(form);
                toggleLoader(form, true);

                $.ajax({
                    url: "{{ route('admin.products.store') }}",
                    method: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        toggleLoader(form, false);
                        $('#addProductModal').modal('hide');
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

            // Edit Product
            $(document).on('click', '.edit-product', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ url('admin/products') }}/" + id + "/edit",
                    method: "GET",
                    success: function(data) {
                        $('#edit_product_id').val(data.id);
                        $('#edit_category_id').val(data.category_id);
                        $('#edit_subtype').val(data.subtype);
                        $('#edit_company').val(data.company);
                        $('#edit_total_landing_wo_gst').val(data.total_landing_wo_gst);
                        $('#edit_gst_percentage').val(data.gst_percentage);
                        $('#edit_tax_amount').val(data.tax_amount);
                        $('#edit_final_landing_with_gst').val(data.final_landing_with_gst);
                        $('#edit_three_kw_dcr_qnt').val(data.three_kw_dcr_qnt);
                        clearErrors($('#editProductForm'));
                        $('#editProductModal').modal('show');
                    }
                });
            });

            // Update Product
            $('#editProductForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let id = $('#edit_product_id').val();
                clearErrors(form);
                toggleLoader(form, true);

                $.ajax({
                    url: "{{ url('admin/products') }}/" + id,
                    method: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        toggleLoader(form, false);
                        $('#editProductModal').modal('hide');
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

            // Delete Product
            $(document).on('click', '.delete-product', function() {
                if (confirm('Are you sure? This will delete the product.')) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('admin/products') }}/" + id,
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
                    url: "{{ url('admin/products') }}/" + id + "/status",
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

            // Open Manage Stock Modal
            $(document).on('click', '.manage-stock', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                $('#stock_product_id').val(id);
                $('.stock-product-name').text(name);
                $('#manageStockForm')[0].reset();
                clearErrors($('#manageStockForm'));
                $('#manageStockModal').modal('show');
            });

            // Update Stock AJAX
            $('#manageStockForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let id = $('#stock_product_id').val();
                clearErrors(form);
                toggleLoader(form, true);

                $.ajax({
                    url: "{{ url('admin/products') }}/" + id + "/stock",
                    method: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        toggleLoader(form, false);
                        $('#manageStockModal').modal('hide');
                        Toastify({
                            text: response.success,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        }).showToast();
                        // Update stock in table without reload
                        $(`.current-stock-display-${id}`).text(response.new_stock);
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

            // View Stock History
            $(document).on('click', '.view-history', function() {
                let id = $(this).data('id');
                $('#stock-history-body').html('<tr><td colspan="5" class="text-center py-2"><span class="spinner-border spinner-border-sm"></span> Loading...</td></tr>');
                $('#stockHistoryModal').modal('show');

                $.ajax({
                    url: "{{ url('admin/products') }}/" + id + "/stock-history",
                    method: "GET",
                    success: function(data) {
                        $('.history-product-name').text(`${data.product.subtype} (${data.product.company})`);
                        let rows = '';
                        if (data.history.length === 0) {
                            rows = '<tr><td colspan="5" class="text-center py-2">No history records found.</td></tr>';
                        } else {
                            data.history.forEach(item => {
                                let date = new Date(item.created_at).toLocaleString();
                                let typeBadge = item.type === 'add' ? 
                                    '<span class="badge bg-light-success">Add In</span>' : 
                                    '<span class="badge bg-light-danger">Less From</span>';
                                let qtyClass = item.type === 'add' ? 'text-success' : 'text-danger';
                                let sign = item.type === 'add' ? '+' : '-';
                                
                                rows += `
                                    <tr>
                                        <td>${date}</td>
                                        <td>${item.user ? item.user.name : 'System'}</td>
                                        <td class="text-center">${typeBadge}</td>
                                        <td class="text-center fw-bold ${qtyClass}">${sign}${item.quantity}</td>
                                        <td class="small">${item.reason || '-'}</td>
                                    </tr>
                                `;
                            });
                        }
                        $('#stock-history-body').html(rows);
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
