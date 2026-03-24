<div class="row">
    <!-- Customer Information -->
    <div class="col-md-6">
        <h6 class="fw-bold text-primary mb-1"><i data-feather="user" class="me-25"></i> Customer Details</h6>
        <table class="table table-sm table-bordered">
            <tr>
                <th class="bg-light w-40">Name</th>
                <td>{{ $lead->customer->name ?? '-' }}</td>
            </tr>
            <tr>
                <th class="bg-light">Mobile</th>
                <td>{{ $lead->customer->mobile ?? '-' }}</td>
            </tr>
            <tr>
                <th class="bg-light">Alt Mobile</th>
                <td>{{ $lead->customer->alternate_mobile ?? '-' }}</td>
            </tr>
            <tr>
                <th class="bg-light">Email</th>
                <td>{{ $lead->customer->email ?? '-' }}</td>
            </tr>
            <tr>
                <th class="bg-light">Address</th>
                <td>
                    {{ $lead->customer->address ?? '-' }},
                    {{ $lead->customer->city ?? '-' }},
                    {{ $lead->customer->state ?? '-' }},
                    {{ $lead->customer->pincode ?? '-' }}
                </td>
            </tr>
            <tr>
                <th class="bg-light">Customer Code</th>
                <td><span class="badge bg-light-primary text-primary">{{ $lead->customer->customer_code ?? '-' }}</span></td>
            </tr>
        </table>
    </div>

    <!-- Lead & Workflow Information -->
    <div class="col-md-6">
        <h6 class="fw-bold text-primary mb-1"><i data-feather="activity" class="me-25"></i> Lead & Workflow</h6>
        <table class="table table-sm table-bordered">
            <tr>
                <th class="bg-light w-40">Lead Number</th>
                <td class="fw-bold text-primary">{{ $lead->lead_no }}</td>
            </tr>
            <tr>
                <th class="bg-light">Current Stage</th>
                <td>
                    <span class="badge bg-info">
                        {{ ucfirst(str_replace('_', ' ', $lead->stage)) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th class="bg-light">Lead Status</th>
                <td>
                    @if ($lead->status == 'completed')
                        <span class="badge bg-success">Completed</span>
                    @elseif($lead->status == 'cancelled')
                        <span class="badge bg-danger">Cancelled</span>
                    @else
                        <span class="badge bg-warning">{{ ucfirst($lead->status) }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th class="bg-light">Priority</th>
                <td>
                    @php
                        $priority = 'Medium';
                        $badgeClass = 'bg-light-warning text-warning';
                        $price = $lead->enquiry->price_quote ?? 0;
                        $size = floatval($lead->enquiry->project_size ?? 0);
                        
                        if ($size >= 5 || $price >= 100000) {
                            $priority = 'High';
                            $badgeClass = 'bg-light-danger text-danger';
                        } elseif ($size <= 2 && $price < 50000 && $price > 0) {
                            $priority = 'Low';
                            $badgeClass = 'bg-light-info text-info';
                        }
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $priority }}</span>
                </td>
            </tr>
            <tr>
                <th class="bg-light">Assigned To</th>
                <td>{{ $lead->assignedUser->name ?? '-' }}</td>
            </tr>
            <tr>
                <th class="bg-light">Created By</th>
                <td>{{ $lead->creator->name ?? 'System' }}</td>
            </tr>
        </table>
    </div>

    <!-- Enquiry / Project Specifications -->
    @if($lead->enquiry)
    <div class="col-12 mt-2">
        <h6 class="fw-bold text-primary mb-1"><i data-feather="settings" class="me-25"></i> Project Specifications (from Enquiry)</h6>
        <div class="row">
            <div class="col-md-3">
                <div class="border p-1 rounded bg-light">
                    <small class="text-muted d-block uppercase mb-25">Solar Type</small>
                    <span class="fw-bold text-dark">{{ $lead->enquiry->solar_type ?? '-' }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border p-1 rounded bg-light">
                    <small class="text-muted d-block uppercase mb-25">Project Size</small>
                    <span class="fw-bold text-dark">{{ $lead->enquiry->project_size ?? '-' }} kW</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border p-1 rounded bg-light">
                    <small class="text-muted d-block uppercase mb-25">Quoted Price</small>
                    <span class="fw-bold text-dark">₹{{ number_format((float)($lead->enquiry->price_quote ?? 0), 2) }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border p-1 rounded bg-light">
                    <small class="text-muted d-block uppercase mb-25">Source</small>
                    <span class="fw-bold text-dark">{{ ucfirst($lead->enquiry->source ?? 'Direct') }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-12 mt-2">
        <h6 class="fw-bold text-primary mb-1"><i data-feather="message-square" class="me-25"></i> Remarks & Timing</h6>
        <div class="border p-1 rounded">
            <p class="mb-1"><strong>Remarks:</strong> {{ $lead->remarks ?? 'No remarks provided.' }}</p>
            <div class="d-flex justify-content-between small text-muted">
                <span><strong>Created At:</strong> {{ $lead->created_at->format('d-m-Y h:i A') }}</span>
                <span><strong>Last Updated:</strong> {{ $lead->updated_at->format('d-m-Y h:i A') }}</span>
            </div>
        </div>
    </div>
</div>
