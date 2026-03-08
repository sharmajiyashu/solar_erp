<table class="table mb-0">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Stage</th>
            <th>Status</th>
            <th>Created By</th>
            <th>Created At</th>
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
                                        'bank',
                                        'discom',
                                        'dispatch',
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

                        </div>
                    </div>
                </td>
            </tr>

            @php $i++; @endphp
        @endforeach

    </tbody>
</table>

@include('admin._pagination', ['data' => $leads])
