@if ($quotation)

    <div class="border">

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

        <div class="row">

            <div class="col-md-6">
                <h6>Lead Name</h6>
                <p>
                    {{ $quotation->lead->name ?? '-' }}
                </p>
            </div>

            <div class="col-md-6 text-end">
                <h6>Status</h6>

                <span class="badge bg-warning">
                    {{ ucfirst($quotation->status) }}
                </span>

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
                    <th class="text-end">Price</th>
                    <th class="text-end">Total</th>
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

                        <td class="text-end">
                            {{ number_format($item->price, 2) }}
                        </td>

                        <td class="text-end">
                            {{ number_format($item->total, 2) }}
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

        <hr>

        <div class="text-end">

            <h6>
                Subtotal :
                <span class="fw-bold">
                    {{ number_format($quotation->subtotal, 2) }}
                </span>
            </h6>

            <h6>
                GST :
                <span class="fw-bold">
                    {{ number_format($quotation->gst_amount, 2) }}
                </span>
            </h6>

            <h4 class="text-success">
                Grand Total :
                {{ number_format($quotation->total_amount, 2) }}
            </h4>

        </div>

    </div>

@endif


@if ($quotation)
    <!-- DELETE BUTTON -->
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteQuotationModal">
        Delete Quotation
    </button>



    <div class="modal fade" id="deleteQuotationModal">
        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
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
@else
    <!-- CREATE BUTTON -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuotationModal">
        + Create Quotation
    </button>
@endif

<div class="modal fade" id="addQuotationModal">
    <div class="modal-dialog modal-lg">

        <form method="POST" action="{{ route('admin.quotations.store', $lead->id) }}">
            @csrf

            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5>Create Quotation</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Quotation Date</label>
                        <input type="date" name="quotation_date" class="form-control" required>
                    </div>

                    <h6>Items</h6>

                    <div id="itemsContainer">

                        <div class="row item-row mb-2">

                            <div class="col-md-4">
                                <input name="items[0][item_name]" placeholder="Item Name" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <input name="items[0][quantity]" placeholder="Qty" type="number" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <input name="items[0][price]" placeholder="Price" type="number" class="form-control">
                            </div>

                            <div class="col-md-3">
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


<script>
    let index = 1;

    function addItemRow() {

        let html = `
<div class="row item-row mb-2">

<div class="col-md-4">
<input name="items[${index}][item_name]"
placeholder="Item Name"
class="form-control">
</div>

<div class="col-md-2">
<input name="items[${index}][quantity]"
type="number"
placeholder="Qty"
class="form-control">
</div>

<div class="col-md-3">
<input name="items[${index}][price]"
type="number"
placeholder="Price"
class="form-control">
</div>

<div class="col-md-3">
<input name="items[${index}][description]"
placeholder="Description"
class="form-control">
</div>

</div>
`;

        document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', html);

        index++;

    }
</script>
