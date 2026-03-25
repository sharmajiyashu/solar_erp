<!-- Procurement Items Management Section -->
@if(auth()->user()->can('items_management view'))
    @can('items_management create')
    <div class="row mb-2">
        <div class="col-12">
            <div class="card bg-light-secondary border-0 shadow-none" style="border-radius: 12px;">
                <div class="card-body">
                    <h5 class="mb-1 fw-bold text-primary">Add Product to Procurement</h5>
                    <form id="addProcurementItemForm" class="row gy-1 align-items-end">
                        @csrf
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-uppercase">Category</label>
                            <select id="proc_category_id" class="form-select select2">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-uppercase">Company</label>
                            <select id="proc_company" class="form-select select2">
                                <option value="">All Companies</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company }}">{{ $company }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase">Product</label>
                            <select name="product_id" id="proc_product_id" class="form-select select2" required>
                                <option value="">Select Product</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}" 
                                        data-category-id="{{ $prod->category_id }}" 
                                        data-company="{{ $prod->company }}"
                                        data-price="{{ $prod->total_landing_wo_gst }}"
                                        data-gst="{{ $prod->gst_percentage }}"
                                        data-tax="{{ $prod->tax_amount }}"
                                        data-total="{{ $prod->final_landing_with_gst }}"
                                        data-stock="{{ $prod->stock }}">
                                        {{ $prod->subtype }} ({{ $prod->company }}) - Stock: {{ $prod->stock }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-uppercase">Quantity</label>
                            <input type="number" name="quantity" id="proc_quantity" class="form-control" value="1" min="1" required>
                        </div>
                        
                        <!-- Hidden Price Fields -->
                        <input type="hidden" name="price" id="proc_price">
                        <input type="hidden" name="gst_percentage" id="proc_gst_percentage">
                        <input type="hidden" name="tax_amount" id="proc_tax_amount">
                        <input type="hidden" name="total" id="proc_total">

                        <div class="col-12 mt-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div id="proc_item_summary" class="small text-muted">
                                    Price: ₹<span id="summ_price">0</span> | GST: <span id="summ_gst">0</span>% | Total: <span class="fw-bold text-dark">₹<span id="summ_total">0</span></span>
                                </div>
                                <button type="submit" class="btn btn-primary d-flex align-items-center">
                                    <i data-feather="plus" class="me-25"></i> Add Item
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <div class="d-flex justify-content-between align-items-center mb-1">
        <h5 class="mb-0 fw-bold text-primary">Procurement Items</h5>
        <div class="btn-group">
            <a href="{{ route('admin.leads.proforma.view', $lead->id) }}" target="_blank" class="btn btn-outline-info btn-sm">
                <i data-feather="eye" class="me-25"></i> View Proforma Invoice
            </a>
            <a href="{{ route('admin.leads.proforma.generate', $lead->id) }}" class="btn btn-outline-primary btn-sm">
                <i data-feather="file-text" class="me-25"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-bordered table-hover" id="procurementItemsTable">
            <thead class="table-light">
                <tr>
                    <th>Item Details</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Base Price</th>
                    <th class="text-center">GST %</th>
                    <th class="text-end">Tax Amount</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lead->procurementItems as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>
                            <div class="fw-bold">{{ $item->product->subtype }}</div>
                            <small class="text-muted">{{ $item->product->company }}</small>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                        <td class="text-center">{{ $item->gst_percentage }}%</td>
                        <td class="text-end">₹{{ number_format($item->tax_amount, 2) }}</td>
                        <td class="text-end fw-bold">₹{{ number_format($item->total, 2) }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-icon btn-flat-danger remove-proc-item" data-id="{{ $item->id }}">
                                <i data-feather="trash-2"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row text-center">
                        <td colspan="7">No items added yet.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="5" class="text-end">Grand Total:</td>
                    <td class="text-end" id="proc-grand-total">₹{{ number_format($lead->procurementItems->sum('total'), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
@else
    <div class="alert alert-warning">
        You do not have permission to view procurement items.
    </div>
@endif

<hr class="my-3">

<!-- Logistics Details Section -->
@can('procurement_management view')
    <form action="{{ route('admin.procurement.store', $lead->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-body">
            <h5 class="mb-2 fw-bold text-primary">Logistics Details</h5>
            <div class="row">

                <!-- Transporter Name -->
                <div class="col-md-6 mb-1">
                    <label class="form-label fw-bold">Transporter Name</label>
                    <input type="text" name="transporter_name" placeholder="Enter transporter name"
                        value="{{ $lead->dispatchDetail->transporter_name ?? '' }}" class="form-control">
                </div>

                <!-- Vehicle Number -->
                <div class="col-md-6 mb-1">
                    <label class="form-label fw-bold">Vehicle Number</label>
                    <input type="text" name="vehicle_number" placeholder="Enter vehicle number"
                        value="{{ $lead->dispatchDetail->vehicle_number ?? '' }}" class="form-control">
                </div>

                <!-- Driver Contact -->
                <div class="col-md-6 mb-1">
                    <label class="form-label fw-bold">Driver Contact</label>
                    <input type="text" name="driver_contact" placeholder="Enter driver contact number"
                        value="{{ $lead->dispatchDetail->driver_contact ?? '' }}" class="form-control">
                </div>

                <!-- Dispatch Date -->
                <div class="col-md-6 mb-1">
                    <label class="form-label fw-bold">Procurement Date</label>
                    <input type="date" name="dispatch_date" value="{{ $lead->dispatchDetail->dispatch_date ?? '' }}"
                        class="form-control">
                </div>

                <div class="col-md-6 mb-1">
                    <label class="form-label fw-bold">Challan Book</label>
                    <input type="file" name="challan_book" class="form-control">
                    @if (!empty($lead->dispatchDetail->challan_book))
                        <div class="mt-50">
                            <a href="{{ url('public/' . $lead->dispatchDetail->challan_book) }}" target="_blank"
                                class="btn btn-sm btn-outline-info">
                                <i data-feather="eye" class="me-25"></i> View File
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-success px-3">
                <i data-feather="save" class="me-25"></i> Save Logistics Details
            </button>
        </div>
    </form>
@else
    <div class="alert alert-warning">
        You do not have permission to manage logistics details.
    </div>
@endcan

@if($lead->stage == 'procurement')
    @if(auth()->user()->can('items_management create') || auth()->user()->can('procurement_management view'))
        <div class="card border-success mt-4">
            <div class="card-body text-center">
                <p>Once the procurement details are saved, you can move the lead to the Installation stage.</p>
                <a href="{{ route('admin.leads.move_stage', [$lead->id, 'installation']) }}" 
                   class="btn btn-lg btn-success {{ !$lead->dispatchDetail ? 'disabled' : '' }}">
                    Move to Installation
                </a>
                @if(!$lead->dispatchDetail)
                    <div class="mt-2 text-danger small">
                        <i class="fas fa-exclamation-triangle"></i> Please save logistics details to enable migration to the next stage.
                    </div>
                @endif
            </div>
        </div>
    @endif
@endif

<!-- JavaScript for Procurement Item Management -->
@push('scripts')
<script>
$(document).ready(function() {
    // Dynamic Product Filtering
    const allProductOptions = $('#proc_product_id option').clone();
    
    function filterProducts() {
        let catId = $('#proc_category_id').val();
        let company = $('#proc_company').val();
        let selector = $('#proc_product_id');

        selector.empty().append('<option value="">Select Product</option>');

        allProductOptions.each(function() {
            let opt = $(this);
            if (opt.val() === "") return;
            
            let optCatId = opt.data('category-id');
            let optCompany = opt.data('company');
            
            let matchesCategory = !catId || (optCatId && optCatId.toString() === catId);
            let matchesCompany = !company || (optCompany && optCompany.toString() === company);
            
            if (matchesCategory && matchesCompany) {
                selector.append(opt.clone());
            }
        });

        if (selector.hasClass('select2-hidden-accessible')) {
            selector.select2();
        }
        selector.trigger('change');
    }

    $('#proc_category_id, #proc_company').on('change', filterProducts);

    // Update prices when product selected
    $('#proc_product_id').on('change', function() {
        let option = $(this).find('option:selected');
        if (option.val()) {
            let price = parseFloat(option.data('price')) || 0;
            let gst = parseFloat(option.data('gst')) || 0;
            let tax = parseFloat(option.data('tax')) || 0;
            let total = parseFloat(option.data('total')) || 0;
            
            $('#proc_price').val(price);
            $('#proc_gst_percentage').val(gst);
            $('#proc_tax_amount').val(tax);
            $('#proc_total').val(total);
            
            $('#summ_price').text(price.toFixed(2));
            $('#summ_gst').text(gst);
            $('#summ_total').text(total.toFixed(2));
            
            calculateRow();
        } else {
            $('#proc_item_summary span').text('0');
        }
    });

    $('#proc_quantity').on('input', calculateRow);

    function calculateRow() {
        let qty = parseInt($('#proc_quantity').val()) || 1;
        let basePrice = parseFloat($('#proc_price').val()) || 0;
        let gstPer = parseFloat($('#proc_gst_percentage').val()) || 0;
        
        let rowBaseTotal = basePrice * qty;
        let rowTax = (rowBaseTotal * gstPer) / 100;
        let rowTotal = rowBaseTotal + rowTax;
        
        $('#proc_tax_amount').val(rowTax.toFixed(2));
        $('#proc_total').val(rowTotal.toFixed(2));
        $('#summ_total').text(rowTotal.toFixed(2));
    }

    // Add Item AJAX
    $('#addProcurementItemForm').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let submitBtn = form.find('button[type="submit"]');
        submitBtn.attr('disabled', true);

        $.ajax({
            url: "{{ route('admin.procurement.addItem', $lead->id) }}",
            method: "POST",
            data: form.serialize(),
            success: function(response) {
                submitBtn.attr('disabled', false);
                Toastify({
                    text: response.success,
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                }).showToast();
                
                // Add row to table
                let item = response.item;
                let newRow = `
                    <tr data-id="${item.id}">
                        <td>
                            <div class="fw-bold">${item.product.subtype}</div>
                            <small class="text-muted">${item.product.company}</small>
                        </td>
                        <td class="text-center">${item.quantity}</td>
                        <td class="text-end">₹${parseFloat(item.price).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        <td class="text-center">${item.gst_percentage}%</td>
                        <td class="text-end">₹${parseFloat(item.tax_amount).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        <td class="text-end fw-bold">₹${parseFloat(item.total).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-icon btn-flat-danger remove-proc-item" data-id="${item.id}">
                                <i data-feather="trash-2"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $('#procurementItemsTable tbody .empty-row').remove();
                $('#procurementItemsTable tbody').append(newRow);
                feather.replace();
                updateGrandTotal();
                
                // Reset form
                form[0].reset();
                $('#proc_product_id').val('').trigger('change');
            },
            error: function(xhr) {
                submitBtn.attr('disabled', false);
                alert(xhr.responseJSON.error || 'Something went wrong');
            }
        });
    });

    // Remove Item AJAX
    $(document).on('click', '.remove-proc-item', function() {
        if (!confirm('Are you sure you want to remove this item and restore stock?')) return;
        
        let id = $(this).data('id');
        let row = $(this).closest('tr');
        
        $.ajax({
            url: "{{ url('admin/procurement-items') }}/" + id,
            method: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                Toastify({
                    text: response.success,
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                }).showToast();
                row.fadeOut(300, function() { 
                    row.remove(); 
                    if ($('#procurementItemsTable tbody tr').length === 0) {
                        $('#procurementItemsTable tbody').append('<tr class="empty-row text-center"><td colspan="7">No items added yet.</td></tr>');
                    }
                    updateGrandTotal();
                });
            }
        });
    });

    function updateGrandTotal() {
        let total = 0;
        $('#procurementItemsTable tbody tr:not(.empty-row)').each(function() {
            let rowTotal = parseFloat($(this).find('td:nth-last-child(2)').text().replace(/[^\d.-]/g, '')) || 0;
            total += rowTotal;
        });
        $('#proc-grand-total').text('₹' + total.toLocaleString(undefined, {minimumFractionDigits: 2}));
    }
});
</script>
@endpush
