<table class="table mb-0">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Stage</th>
            <th>Status</th>
            <th>Created By</th>
            <th>Created At</th>
            <th>Payments</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @php
            $i = ($leads->currentPage() - 1) * $leads->perPage() + 1;
        @endphp

        @foreach ($leads as $item)
            <tr>
                <td>{{ $i }}</td>

                <!-- Customer -->
                <td>
                    <div class="fw-bolder">
                        {{ $item->customer->name ?? '-' }}
                    </div>
                    <div class="text-muted">
                        {{ $item->customer->mobile ?? '-' }}
                    </div>
                    <div class="text-muted">
                        {{ $item->lead_no }}
                    </div>
                </td>

                <!-- Stage -->
                <td>
                    <span class="badge bg-primary">
                        {{ ucfirst(str_replace('_', ' ', $item->stage)) }}
                    </span>
                </td>

                <!-- Status -->
                <td>
                    @if ($item->status == 'in_progress')
                        <span class="badge bg-warning">In Progress</span>
                    @elseif($item->status == 'completed')
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-secondary">
                            {{ ucfirst($item->status) }}
                        </span>
                    @endif
                </td>

                <!-- Assigned User -->
                <td>
                    {{ $item->creator->name ?? '-' }}
                </td>

                <!-- Created -->
                <td>
                    {{ $item->created_at->format('d-m-Y h:i A') }}
                </td>

                <!-- Payments -->
                <td>
                    <div class="d-flex flex-column gap-1">
                        @if($item->token_received)
                            <span class="badge badge-light-success text-start" style="font-size: 0.7rem;">T: ₹{{ number_format($item->token_amount) }}</span>
                        @endif
                        @if($item->first_payment_received)
                            <span class="badge badge-light-info text-start" style="font-size: 0.7rem;">1st: ₹{{ number_format($item->first_tranche_amount) }}</span>
                        @endif
                        @if(optional($item->verificationReport)->second_tier_payment_received)
                            <span class="badge badge-light-primary text-start" style="font-size: 0.7rem;">2nd: ₹{{ number_format(optional($item->verificationReport)->second_tranche_amount) }}</span>
                        @endif
                        @if(!$item->token_received && !$item->first_payment_received && !optional($item->verificationReport)->second_tier_payment_received)
                            <span class="text-muted small">-</span>
                        @endif
                    </div>
                </td>

                <!-- Actions -->
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            Action
                        </button>

                        <div class="dropdown-menu dropdown-menu-end">

                            <a class="dropdown-item" href="{{ route('admin.leads.show', $item->id) }}">
                                View Details
                            </a>

                            @if ($item->stage != 'completed')
                                @php
                                    $stages = [
                                        'site_visit',
                                        'quotation',
                                        'document',
                                        'backend',
                                        'procurement',
                                        'installation',
                                        'verification',
                                        'completed',
                                    ];
                                    $currentIndex = array_search($item->stage, $stages);
                                    $nextStage = $stages[$currentIndex + 1] ?? null;
                                @endphp

                                @if ($nextStage)
                                    <a class="dropdown-item"
                                        href="{{ route('admin.leads.move_stage', [$item->id, $nextStage]) }}">
                                        Move To
                                        {{ ucfirst(str_replace('_', ' ', $nextStage)) }}
                                    </a>
                                @endif
                            @endif

                            <a class="dropdown-item" href="javascript:void(0);" 
                               onclick="event.preventDefault(); if(confirm('Are you sure you want to own this lead?')) document.getElementById('own-form-{{ $item->id }}').submit();">
                                Own Lead
                            </a>
                            <form id="own-form-{{ $item->id }}" action="{{ route('admin.leads.own', $item->id) }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                            <a class="dropdown-item text-danger" href="javascript:void(0);" 
                               onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this lead?')) document.getElementById('delete-form-{{ $item->id }}').submit();">
                                Delete
                            </a>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('admin.leads.destroy', $item->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>

                        </div>
                    </div>
                </td>
            </tr>

            @php $i++; @endphp
        @endforeach

    </tbody>
</table>

@include('admin._pagination', ['data' => $leads])
