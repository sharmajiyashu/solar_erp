@php
    $stages = $lead->project_stages ?? [];

    if (!is_array($stages)) {
        $stages = json_decode($stages, true) ?? [];
    }
@endphp

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Stage</th>
            <th>Status</th>
            <th>Completed At</th>
        </tr>
    </thead>

    <tbody>

        @foreach (\App\Models\Lead::$workflowStages as $stageKey)
            @php
                $stageData = $stages[$stageKey] ?? [
                    'status' => 'pending',
                    'completed_at' => null,
                ];
            @endphp

            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $stageKey)) }}</td>

                <td>
                    @if ($stageData['status'] == 'done')
                        <span class="badge bg-success">Done</span>
                    @else
                        <span class="badge bg-warning">
                            {{ ucfirst($stageData['status']) }}
                        </span>
                    @endif
                </td>

                <td>
                    {{ $stageData['completed_at'] ? \Carbon\Carbon::parse($stageData['completed_at'])->format('d-m-Y h:i A') : '-' }}
                </td>
            </tr>
        @endforeach

    </tbody>
</table>

<div class="mt-4">
    @include('admin.leads.partials.completed_form')
</div>
