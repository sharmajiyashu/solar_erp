@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>

    <div class="content-wrapper container-xxl p-0">
        

        <div class="content-body">
            <section id="analysis-section">
                <div class="row match-height">
                    <!-- Row 1: Product Selection and Summary -->
                    <div class="col-md-7 mb-2">
                        <div class="card border-0 h-100" style="border-radius: 12px; box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);">
                            <div class="card-header border-bottom py-2">
                                <h4 class="card-title" style="font-weight: 600;">Add Product</h4>
                            </div>
                            <div class="card-body py-2">
                                <div class="row mb-1">
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label fw-bold small text-uppercase" style="color: #5e5873;">Filter Category</label>
                                        <select id="category-filter" class="form-select select2" style="padding: 0.75rem; border-radius: 8px;">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label fw-bold small text-uppercase" style="color: #5e5873;">Filter Company</label>
                                        <select id="company-filter" class="form-select select2" style="padding: 0.75rem; border-radius: 8px;">
                                            <option value="">All Companies</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company }}">{{ $company }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-end">
                                    <div class="col-md-7 mb-1">
                                        <label class="form-label fw-bold small text-uppercase" style="color: #5e5873;">Select Product (Item)</label>
                                        <select id="product-selector" class="form-select select2" style="padding: 0.75rem; border-radius: 8px;">
                                            <option value="">Choose a product...</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" 
                                                    data-name="{{ $product->subtype }} ({{ $product->company }})" 
                                                    data-landing="{{ $product->total_landing_wo_gst }}"
                                                    data-gst="{{ $product->gst_percentage }}"
                                                    data-tax="{{ $product->tax_amount }}"
                                                    data-price="{{ $product->final_landing_with_gst }}"
                                                    data-category-id="{{ $product->category_id }}"
                                                    data-company="{{ $product->company }}"
                                                    data-category="{{ $product->category->name ?? 'N/A' }}">
                                                    {{ $product->subtype }} - {{ $product->company }} (₹{{ number_format($product->final_landing_with_gst, 2) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-1">
                                        <label class="form-label fw-bold small text-uppercase" style="color: #5e5873;">Qty</label>
                                        <input type="number" id="product-qty" class="form-control" value="1" min="1" style="padding: 0.75rem; border-radius: 8px;">
                                    </div>
                                    <div class="col-md-3 mb-1 text-end">
                                        <button id="add-to-analysis" class="btn btn-primary w-100 py-1" style="border-radius: 8px; font-weight: 600; box-shadow: 0 4px 14px 0 rgba(113, 187, 178, 0.4);">
                                            <i data-feather="plus" class="me-50"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 mb-2">
                        <!-- Summary Card -->
                        <div class="card border-0 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #71bbb2 0%, #54a59a 100%); box-shadow: 0 4px 24px 0 rgba(113, 187, 178, 0.3);">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <h4 class="text-white mb-2" style="font-weight: 600;">Analysis Summary</h4>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-white-50">Total Items:</span>
                                        <span id="summary-items" class="text-white fw-bold">0</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-white-50">Total Landing (WO GST):</span>
                                        <span id="summary-landing" class="text-white fw-bold">₹0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-white-50">Total Tax:</span>
                                        <span id="summary-tax" class="text-white fw-bold">₹0.00</span>
                                    </div>
                                </div>
                                <div>
                                    <hr style="border-color: rgba(255,255,255,0.1);">
                                    <div class="d-flex flex-column">
                                        <span class="text-white-50 small text-uppercase">Total Final Cost</span>
                                        <span id="summary-total" class="text-white" style="font-size: 1.75rem; font-weight: 700;">₹0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-1">
                    <!-- Row 2: Analysis Table -->
                    <div class="col-md-12">
                        <div class="card border-0" style="border-radius: 12px; box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1); background-color: #fcfcfc;">
                            <div class="card-header border-bottom py-2 d-flex justify-content-between align-items-center">
                                <h4 class="card-title" style="font-weight: 600;">Selected Items Analysis</h4>
                                <button id="clear-all" class="btn btn-sm btn-outline-danger" style="border-radius: 6px;">
                                    <i data-feather="trash-2" class="me-25" style="width: 14px; height: 14px;"></i> Clear All
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="analysis-table" class="table table-hover mb-0">
                                        <thead style="background-color: #f1f1f1;">
                                            <tr>
                                                <th class="ps-2 py-1" style="text-transform: uppercase; font-size: 0.7rem; font-weight: 600; color: #5e5873;">Product</th>
                                                <th class="py-1 text-center" style="text-transform: uppercase; font-size: 0.7rem; font-weight: 600; color: #5e5873;">Landing</th>
                                                <th class="py-1 text-center" style="text-transform: uppercase; font-size: 0.7rem; font-weight: 600; color: #5e5873;">GST %</th>
                                                <th class="py-1 text-center" style="text-transform: uppercase; font-size: 0.7rem; font-weight: 600; color: #5e5873;">Tax</th>
                                                <th class="py-1 text-center" style="width: 70px; text-transform: uppercase; font-size: 0.7rem; font-weight: 600; color: #5e5873;">Qty</th>
                                                <th class="py-1 text-end pe-2" style="text-transform: uppercase; font-size: 0.7rem; font-weight: 600; color: #5e5873;">Total (₹)</th>
                                                <th class="py-1 text-center pe-2" style="width: 40px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="empty-row">
                                                <td colspan="7" class="text-center py-5">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i data-feather="shopping-cart" class="text-muted mb-1" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                                                        <p class="text-muted font-medium-2">Add products to start analysis</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="d-none border-top">
                                            <tr style="background-color: #f8f9fa;">
                                                <td colspan="5" class="text-end fw-bold ps-2 py-2 small text-uppercase">Total Landing WO GST:</td>
                                                <td class="text-end fw-bold py-2 pe-3" id="total-landing-footer">₹0.00</td>
                                                <td></td>
                                            </tr>
                                            <tr style="background-color: #f8f9fa;">
                                                <td colspan="5" class="text-end fw-bold ps-2 py-1 small text-uppercase">Total GST Tax:</td>
                                                <td class="text-end fw-bold py-1 pe-3" id="total-tax-footer">₹0.00</td>
                                                <td></td>
                                            </tr>
                                            <tr style="background-color: #f8f9fa; border-top: 1px solid #ddd;">
                                                <td colspan="5" class="text-end fw-bold ps-2 py-2 text-uppercase">Grand Total Final:</td>
                                                <td class="text-end fw-bold text-primary py-2 pe-3" id="grand-total-footer" style="font-size: 1.1rem;">₹0.00</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Clear All Confirmation Modal -->
<div class="modal fade modal-danger text-start" id="clearAllModal" tabindex="-1" aria-labelledby="clearAllModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header bg-transparent border-0 pb-0">
                <h5 class="modal-title" id="clearAllModalLabel" style="font-weight: 700;">Clear All Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove all items from the analysis list? This action cannot be undone.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                <button type="button" id="confirm-clear-all" class="btn btn-danger" style="border-radius: 8px; font-weight: 600;">Clear Everything</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let analysisItems = [];
        const allProductOptions = $('#product-selector option').clone();

        function filterProducts() {
            const categoryId = $('#category-filter').val();
            const company = $('#company-filter').val();
            const selector = $('#product-selector');
            
            selector.empty().append('<option value="">Choose a product...</option>');
            
            allProductOptions.each(function() {
                const opt = $(this);
                if (opt.val() === "") return;
                
                const optCatId = opt.data('category-id');
                const optCompany = opt.data('company');
                
                const matchesCategory = categoryId === "" || (optCatId && optCatId.toString() === categoryId);
                const matchesCompany = company === "" || (optCompany && optCompany.toString() === company);
                
                if (matchesCategory && matchesCompany) {
                    selector.append(opt.clone());
                }
            });
            
            // Re-initialize or trigger select2 if it's being used
            if (selector.hasClass('select2-hidden-accessible')) {
                selector.select2();
            }
            selector.trigger('change');
        }

        $('#category-filter, #company-filter').on('change', function() {
            filterProducts();
        });

        function renderTable() {
            const tbody = $('#analysis-table tbody');
            const tfoot = $('#analysis-table tfoot');
            
            if (analysisItems.length === 0) {
                tbody.html(`
                    <tr id="empty-row">
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i data-feather="shopping-cart" class="text-muted mb-1" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                                <p class="text-muted font-medium-2">Add products to start analysis</p>
                            </div>
                        </td>
                    </tr>
                `);
                tfoot.addClass('d-none');
                $('#summary-items').text('0');
                $('#summary-landing').text('₹0.00');
                $('#summary-tax').text('₹0.00');
                $('#summary-total').text('₹0.00');
                if (feather) feather.replace();
                return;
            }

            tbody.empty();
            tfoot.removeClass('d-none');
            
            let totalLanding = 0;
            let totalTax = 0;
            let grandTotal = 0;
            let totalItemsCount = 0;

            analysisItems.forEach((item, index) => {
                const subTotalLanding = item.landing * item.qty;
                const subTotalTax = item.tax * item.qty;
                const subTotalFinal = item.price * item.qty;

                totalLanding += subTotalLanding;
                totalTax += subTotalTax;
                grandTotal += subTotalFinal;
                totalItemsCount += parseInt(item.qty);

                tbody.append(`
                    <tr>
                        <td class="ps-2 py-1 align-middle">
                            <div class="d-flex flex-column">
                                <span class="fw-bold" style="font-size: 0.9rem;">${item.name}</span>
                                <small class="text-muted">${item.category}</small>
                            </div>
                        </td>
                        <td class="py-1 align-middle text-center small text-muted">₹${parseFloat(item.landing).toLocaleString('en-IN', {minimumFractionDigits: 2})}</td>
                        <td class="py-1 align-middle text-center small text-muted">${item.gst}%</td>
                        <td class="py-1 align-middle text-center small text-muted">₹${parseFloat(item.tax).toLocaleString('en-IN', {minimumFractionDigits: 2})}</td>
                        <td class="py-1 align-middle text-center">
                            <div class="input-group input-group-sm" style="width: 70px; margin: 0 auto;">
                                <input type="number" class="form-control text-center update-qty p-0" data-index="${index}" value="${item.qty}" min="1">
                            </div>
                        </td>
                        <td class="py-1 align-middle text-end fw-bold pe-2" style="font-size: 0.95rem;">₹${subTotalFinal.toLocaleString('en-IN', {minimumFractionDigits: 1})}</td>
                        <td class="py-1 align-middle text-center pe-1">
                            <button class="btn btn-icon btn-flat-danger btn-sm p-25 remove-item" data-index="${index}">
                                <i data-feather="x" style="width: 14px; height: 14px;"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            $('#total-landing-footer').text(`₹${totalLanding.toLocaleString('en-IN', {minimumFractionDigits: 2})}`);
            $('#total-tax-footer').text(`₹${totalTax.toLocaleString('en-IN', {minimumFractionDigits: 2})}`);
            $('#grand-total-footer').text(`₹${grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})}`);
            
            $('#summary-items').text(totalItemsCount);
            $('#summary-landing').text(`₹${totalLanding.toLocaleString('en-IN', {minimumFractionDigits: 2})}`);
            $('#summary-tax').text(`₹${totalTax.toLocaleString('en-IN', {minimumFractionDigits: 2})}`);
            $('#summary-total').text(`₹${grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})}`);
            
            if (feather) feather.replace();
        }

        $('#add-to-analysis').on('click', function() {
            const selector = $('#product-selector');
            const selected = selector.find(':selected');
            const productId = selector.val();
            
            if (!productId) {
                Toastify({
                    text: "Please select a product first",
                    backgroundColor: "#ea5455",
                }).showToast();
                return;
            }

            const qty = parseInt($('#product-qty').val());
            if (qty < 1) return;

            // Check if product already exists
            const existingIndex = analysisItems.findIndex(i => i.id === productId);
            if (existingIndex > -1) {
                analysisItems[existingIndex].qty += qty;
            } else {
                analysisItems.push({
                    id: productId,
                    name: selected.data('name'),
                    landing: parseFloat(selected.data('landing')),
                    gst: parseFloat(selected.data('gst')),
                    tax: parseFloat(selected.data('tax')),
                    price: parseFloat(selected.data('price')),
                    category: selected.data('category'),
                    qty: qty
                });
            }

            renderTable();
            selector.val('').trigger('change');
            $('#product-qty').val(1);
            
            Toastify({
                text: "Product added to analysis",
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
            }).showToast();
        });

        $(document).on('click', '.remove-item', function() {
            const index = $(this).data('index');
            analysisItems.splice(index, 1);
            renderTable();
        });

        $(document).on('change input', '.update-qty', function() {
            const index = $(this).data('index');
            let qty = parseInt($(this).val());
            if (isNaN(qty) || qty < 1) qty = 1;
            analysisItems[index].qty = qty;
            renderTable();
        });

        $('#clear-all').on('click', function() {
            if (analysisItems.length > 0) {
                $('#clearAllModal').modal('show');
            }
        });

        $('#confirm-clear-all').on('click', function() {
            analysisItems = [];
            renderTable();
            $('#clearAllModal').modal('hide');
            Toastify({
                text: "All items cleared",
                backgroundColor: "#ea5455",
            }).showToast();
        });
    });
</script>
@endpush
@endsection
