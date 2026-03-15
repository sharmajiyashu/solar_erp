@if ($quotation)
    <div class="border p-1">

        <div class="row">

            <div class="col-md-6">
                <h5>Quotation No</h5>
                <p class="fw-bold text-danger">
                    {{ $quotation->quotation_no }}
                </p>
            </div>

            <div class="col-md-6 text-end">
                <h5>Date</h5>
                <p>
                    {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d-m-Y') }}
                </p>
            </div>

        </div>

        <hr>

        <h5 class="mb-3">Quotation Items</h5>

        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th class="text-center">Qty</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($quotation->items as $key => $item)
                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>
                            <strong>{{ $item->item_name }}</strong>
                        </td>

                        <td>
                            {{ $item->description ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $item->quantity }}
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

        <hr>

        <div class="text-end">

            <h6>
                Total Amount :
                <span class="fw-bold">
                    {{ number_format($quotation->total_amount, 2) }}
                </span>
            </h6>

        </div>

    </div>
@endif


@if ($quotation)
    <!-- DELETE BUTTON -->
    @can('quotations delete')
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteQuotationModal">
            Delete Quotation
        </button>



        <div class="modal fade" id="deleteQuotationModal">
            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Confirm Delete</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <h5>Are you sure you want to delete this quotation?</h5>
                    </div>

                    <div class="modal-footer">

                        <form method="POST" action="{{ route('admin.quotations.destroy', $quotation->id) }}">

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
    @endcan
@else
    <!-- CREATE BUTTON -->
    @can('quotations create')
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuotationModal">
            + Create Quotation
        </button>
    @endcan
@endif

@can('quotations create')
    <div class="modal fade" id="addQuotationModal">
        <div class="modal-dialog modal-lg">

            <form method="POST" action="{{ route('admin.quotations.store', $lead->id) }}">
                @csrf

                <div class="modal-content">

                    <div class="modal-header ">
                        <h5>Create Quotation</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label>Total Amount</label>
                                <input type="number" name="total_amount" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>Quotation Date</label>
                                <input type="date" name="quotation_date" class="form-control" required>
                            </div>

                        </div>



                        <h6>Items</h6>

                        <div id="itemsContainer">

                            <div class="row item-row mb-2">

                                <div class="col-md-5">
                                    <input name="items[0][item_name]" placeholder="Item Name" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <input name="items[0][quantity]" placeholder="Qty" type="number" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <input name="items[0][description]" placeholder="Description" class="form-control">
                                </div>

                            </div>

                        </div>

                        <button type="button" class="btn btn-sm btn-success" onclick="addItemRow()">
                            + Add Item
                        </button>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Save Quotation</button>
                    </div>

                </div>

            </form>

        </div>
    </div>
@endcan


<script>
    let index = 1;

    function addItemRow() {

        let html = `
<div class="row item-row mb-2">

<div class="col-md-5">
<input name="items[${index}][item_name]" placeholder="Item Name" class="form-control">
</div>

<div class="col-md-3">
<input name="items[${index}][quantity]" type="number" placeholder="Qty" class="form-control">
</div>

<div class="col-md-4">
<input name="items[${index}][description]" placeholder="Description" class="form-control">
</div>

</div>
`;

        document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', html);

        index++;

    }
</script>

@if ($lead->stage == 'quotation')
    @can('quotations create')
        <div class="card border-primary mt-4">
            <div class="card-body text-center">
                <p>Once the quotation is created, you can move the lead to the Document stage.</p>
                <a href="{{ route('admin.leads.move_stage', [$lead->id, 'document']) }}" 
                   class="btn btn-lg btn-primary {{ !$quotation ? 'disabled' : '' }}">
                    Move to Document
                </a>
                @if(!$quotation)
                    <div class="mt-2 text-danger small">
                        <i class="fas fa-exclamation-triangle"></i> Please create a quotation to enable this button.
                    </div>
                @endif
            </div>
        </div>
    @endcan
@endif
