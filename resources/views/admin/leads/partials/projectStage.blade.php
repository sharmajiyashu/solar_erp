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

@if($lead->documents()->where('document_type', 'Completion Photo')->exists())
    <div class="mt-4 border-top pt-3">
        <h5 class="mb-3">Project Completion Photos</h5>
        <div class="row">
            @foreach($lead->documents()->where('document_type', 'Completion Photo')->get() as $photo)
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm">
                        <a href="{{ url('public/' . $photo->file_path) }}" target="_blank">
                            <img src="{{ url('public/' . $photo->file_path) }}" class="card-img-top img-thumbnail" style="height: 150px; object-fit: cover;" alt="Completion Photo">
                        </a>
                        <div class="card-body p-1 text-center">
                            <small class="text-muted">{{ $photo->created_at->format('d-m-Y') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
