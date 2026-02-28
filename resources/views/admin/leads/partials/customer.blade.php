<table class="table table-bordered">

    <tr>
        <th>Customer Name</th>
        <td>{{ $lead->customer->name ?? '-' }}</td>
    </tr>

    <tr>
        <th>Mobile</th>
        <td>{{ $lead->customer->mobile ?? '-' }}</td>
    </tr>

    <tr>
        <th>Alternate Mobile</th>
        <td>{{ $lead->customer->alternate_mobile ?? '-' }}</td>
    </tr>

    <tr>
        <th>Email</th>
        <td>{{ $lead->customer->email ?? '-' }}</td>
    </tr>

    <tr>
        <th>Address</th>
        <td>
            {{ $lead->customer->address ?? '-' }},
            {{ $lead->customer->city ?? '-' }},
            {{ $lead->customer->state ?? '-' }},
            {{ $lead->customer->pincode ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Customer Code</th>
        <td>{{ $lead->customer->customer_code ?? '-' }}</td>
    </tr>

</table>
