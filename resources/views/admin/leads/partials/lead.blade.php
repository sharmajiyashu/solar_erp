<table class="table table-bordered">

    <tr>
        <th>Lead Number</th>
        <td>{{ $lead->lead_no }}</td>
    </tr>

    <tr>
        <th>Stage</th>
        <td>
            <span class="badge bg-info">
                {{ ucfirst(str_replace('_', ' ', $lead->stage)) }}
            </span>
        </td>
    </tr>

    <tr>
        <th>Status</th>
        <td>
            @if ($lead->status == 'completed')
                <span class="badge bg-success">Completed</span>
            @elseif($lead->status == 'cancelled')
                <span class="badge bg-danger">Cancelled</span>
            @else
                <span class="badge bg-warning">
                    {{ ucfirst($lead->status) }}
                </span>
            @endif
        </td>
    </tr>

    <tr>
        <th>Assigned To</th>
        <td>{{ $lead->assignedUser->name ?? '-' }}</td>
    </tr>

    <tr>
        <th>Remarks</th>
        <td>{{ $lead->remarks ?? '-' }}</td>
    </tr>

    <tr>
        <th>Created By</th>
        <td>{{ $lead->creator->name ?? '-' }}</td>
    </tr>

    <tr>
        <th>Created At</th>
        <td>{{ $lead->created_at->format('d-m-Y h:i A') }}</td>
    </tr>

</table>
