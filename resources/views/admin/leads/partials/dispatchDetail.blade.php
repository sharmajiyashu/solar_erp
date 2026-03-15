@can('procurement_management create')
    <form action="{{ route('admin.procurement.store', $lead->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-body">

            <div class="row">

                <!-- Transporter Name -->
                <div class="col-md-6 mb-1">
                    <label>Transporter Name</label>
                    <input type="text" name="transporter_name" placeholder="Enter transporter name"
                        value="{{ $lead->dispatchDetail->transporter_name ?? '' }}" class="form-control">
                </div>

                <!-- Vehicle Number -->
                <div class="col-md-6 mb-1">
                    <label>Vehicle Number</label>
                    <input type="text" name="vehicle_number" placeholder="Enter vehicle number"
                        value="{{ $lead->dispatchDetail->vehicle_number ?? '' }}" class="form-control">
                </div>

                <!-- Driver Contact -->
                <div class="col-md-6 mb-1">
                    <label>Driver Contact</label>
                    <input type="text" name="driver_contact" placeholder="Enter driver contact number"
                        value="{{ $lead->dispatchDetail->driver_contact ?? '' }}" class="form-control">
                </div>

                <!-- Dispatch Date -->
                <div class="col-md-6 mb-1">
                    <label>Procurement Date</label>
                    <input type="date" name="dispatch_date" value="{{ $lead->dispatchDetail->dispatch_date ?? '' }}"
                        class="form-control">
                </div>

                <div class="col-md-6 mb-1">
                    <label>Challan Book</label>

                    <input type="file" name="challan_book" class="form-control">

                    @if (!empty($lead->dispatchDetail->challan_book))
                        <a href="{{ url('public/' . $lead->dispatchDetail->challan_book) }}" target="_blank"
                            class="btn btn-sm btn-info">
                            View File
                        </a>
                    @endif

                </div>

                <!-- Status -->
                <div class="col-md-6 mb-1">
                    <label>Status</label>

                    <select name="status" class="form-control">

                        <option value="packed" {{ optional($lead->dispatchDetail)->status == 'packed' ? 'selected' : '' }}>
                            Packed
                        </option>

                        <option value="dispatched"
                            {{ optional($lead->dispatchDetail)->status == 'dispatched' ? 'selected' : '' }}>
                            Procured
                        </option>

                        <option value="delivered"
                            {{ optional($lead->dispatchDetail)->status == 'delivered' ? 'selected' : '' }}>
                            Delivered
                        </option>

                        <option value="cancelled"
                            {{ optional($lead->dispatchDetail)->status == 'cancelled' ? 'selected' : '' }}>
                            Cancelled
                        </option>

                    </select>

                </div>

            </div>

        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">
                Close
            </button>

            <button class="btn btn-success">
                Save Procurement Details
            </button>
        </div>

    </form>
@else
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 mb-1">
                <label>Transporter Name</label>
                <p><strong>{{ $lead->dispatchDetail->transporter_name ?? 'N/A' }}</strong></p>
            </div>
            <div class="col-md-6 mb-1">
                <label>Vehicle Number</label>
                <p><strong>{{ $lead->dispatchDetail->vehicle_number ?? 'N/A' }}</strong></p>
            </div>
            <div class="col-md-6 mb-1">
                <label>Driver Contact</label>
                <p><strong>{{ $lead->dispatchDetail->driver_contact ?? 'N/A' }}</strong></p>
            </div>
            <div class="col-md-6 mb-1">
                <label>Procurement Date</label>
                <p><strong>{{ $lead->dispatchDetail->dispatch_date ?? 'N/A' }}</strong></p>
            </div>
            <div class="col-md-6 mb-1">
                <label>Status</label>
                <p><strong>{{ ucfirst($lead->dispatchDetail->status ?? 'N/A') }}</strong></p>
            </div>
            <div class="col-md-6 mb-1">
                <label>Challan Book</label>
                @if (!empty($lead->dispatchDetail->challan_book))
                    <a href="{{ url('public/' . $lead->dispatchDetail->challan_book) }}" target="_blank"
                        class="btn btn-sm btn-info">
                        View File
                    </a>
                @else
                    <p><strong>N/A</strong></p>
                @endif
            </div>
        </div>
    </div>
@endcan
@if($lead->stage == 'procurement')
    @can('procurement_management create')
        <div class="card border-success mt-4">
            <div class="card-body text-center">
                <p>Once the procurement details are saved, you can move the lead to the Installation stage.</p>
                
                <a href="{{ route('admin.leads.move_stage', [$lead->id, 'installation']) }}" 
                   class="btn btn-lg btn-success {{ !$lead->dispatchDetail ? 'disabled' : '' }}">
                    Move to Installation
                </a>
                
                @if(!$lead->dispatchDetail)
                    <div class="mt-2 text-danger small">
                        <i class="fas fa-exclamation-triangle"></i> Please save procurement details to enable this button.
                    </div>
                @endif
            </div>
        </div>
    @endcan
@endif
