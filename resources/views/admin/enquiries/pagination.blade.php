 <table class="table mb-0">
     <thead class="table-dark">
         <tr>
             <th>#</th>
             <th>Customer</th>
             <th>Status</th>
             <th>Next Followup</th>
             <th>Created By</th>
             <th>Created At</th>
             <th>Action</th>
         </tr>
     </thead>
     <tbody>
         @php
             $i = ($enquiries->currentPage() - 1) * $enquiries->perPage() + 1;
         @endphp

         @foreach ($enquiries as $item)
             <tr>
                 <td>{{ $i }}</td>

                 <td>
                     <div class="fw-bolder">
                         {{ $item->customer_name }}
                     </div>
                     <div class="text-muted">
                         {{ $item->mobile }}
                     </div>
                     <div class="text-muted">
                         {{ $item->enquiry_no }}
                     </div>
                 </td>

                 <td>
                     <span class="badge bg-info">
                         {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                     </span>
                 </td>

                 <td>
                     {{ $item->next_followup_date ? date('d-m-Y', strtotime($item->next_followup_date)) : '-' }}
                 </td>

                 <td>
                     {{ $item->creator->name ?? '-' }}
                 </td>

                 <td>
                     {{ date('d-m-Y h:i a', strtotime($item->created_at)) }}
                 </td>

                 <td>
                     <div class="dropdown">
                         <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                             Action
                         </button>

                         <div class="dropdown-menu dropdown-menu-end">

                             <a class="dropdown-item" href="{{ route('admin.enquiries.show', $item->id) }}">
                                 View / Followups
                             </a>

                             <a class="dropdown-item" href="{{ route('admin.enquiries.edit', $item->id) }}">
                                 Edit
                             </a>

                             @if ($item->status != 'converted_to_lead')
                                 <a class="dropdown-item" href="{{ route('admin.enquiries.convert', $item->id) }}">
                                     Convert to Lead
                                 </a>
                             @endif

                             @if ($item->status != 'closed')
                                 <a class="dropdown-item" href="{{ route('admin.enquiries.close', $item->id) }}">
                                     Close
                                 </a>
                             @endif

                             <form action="{{ route('admin.enquiries.destroy', $item->id) }}" method="POST">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="dropdown-item text-danger">
                                     Delete
                                 </button>
                             </form>

                         </div>
                     </div>
                 </td>
             </tr>
             @php $i++; @endphp
         @endforeach
     </tbody>
 </table>

 @include('admin._pagination', ['data' => $enquiries])
