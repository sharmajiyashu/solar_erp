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

                   {{-- STATUS --}}
                   <td>
                       @switch($item->status)
                           @case('pending')
                               <span class="badge bg-warning">Pending</span>
                           @break

                           @case('next_followup')
                               <span class="badge bg-info">Next Followup</span>
                           @break

                           @case('converted_to_lead')
                               <span class="badge bg-success">Converted To Lead</span>
                           @break

                           @case('mark_to_close')
                               <span class="badge bg-secondary">Mark To Close</span>
                           @break

                           @case('closed')
                               <span class="badge bg-danger">Closed</span>
                           @break
                       @endswitch
                   </td>

                   {{-- NEXT FOLLOWUP --}}
                   <td>
                       {{ $item->next_followup_date ? \Carbon\Carbon::parse($item->next_followup_date)->format('d-m-Y') : '-' }}
                   </td>

                   <td>
                       {{ $item->creator->name ?? '-' }}
                   </td>

                   <td>
                       {{ $item->created_at->format('d-m-Y h:i A') }}
                   </td>

                   {{-- ACTIONS --}}
                   <td>
                       <div class="dropdown">
                           <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                               Action
                           </button>

                           <div class="dropdown-menu dropdown-menu-end">

                               {{-- VIEW --}}
                               @can('enquiries view')
                                   <a class="dropdown-item" href="{{ route('admin.enquiries.show', $item->id) }}">
                                       View / Followups
                                   </a>
                               @endcan

                               {{-- EDIT --}}
                               @can('enquiries edit')
                                   <a class="dropdown-item" href="{{ route('admin.enquiries.edit', $item->id) }}">
                                       Edit
                                   </a>
                               @endcan

                               {{-- CONVERT --}}
                               @can('enquiries convert_to_lead')
                                   @if ($item->status != 'converted_to_lead')
                                       <a class="dropdown-item" href="{{ route('admin.enquiries.convert', $item->id) }}">
                                           Convert to Lead
                                       </a>
                                   @endif
                               @endcan

                               {{-- MARK TO CLOSE --}}
                               @can('enquiries mark_to_close')
                                   @if ($item->status != 'mark_to_close')
                                       <a class="dropdown-item"
                                           href="{{ route('admin.enquiries.markToClose', $item->id) }}">
                                           Mark To Close
                                       </a>
                                   @endif
                               @endcan

                               {{-- CLOSE --}}
                               @can('enquiries close')
                                   @if ($item->status != 'closed')
                                       <a class="dropdown-item" href="{{ route('admin.enquiries.close', $item->id) }}">
                                           Close
                                       </a>
                                   @endif
                               @endcan

                               {{-- DELETE --}}
                               @can('enquiries delete')
                                   <a class="dropdown-item text-danger" data-bs-toggle="modal"
                                       data-bs-target="#deleteModal{{ $item->id }}">
                                       Delete
                                   </a>
                               @endcan

                           </div>
                       </div>
                   </td>
               </tr>

               {{-- DELETE CONFIRMATION MODAL --}}
               @can('enquiries delete')
                   <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                       <div class="modal-dialog modal-dialog-centered">
                           <div class="modal-content">

                               <div class="modal-header">
                                   <h5 class="modal-title">Confirm Delete</h5>
                                   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                               </div>

                               <div class="modal-body">
                                   Are you sure you want to delete
                                   <strong>{{ $item->customer_name }}</strong>?
                               </div>

                               <div class="modal-footer">
                                   <form action="{{ route('admin.enquiries.destroy', $item->id) }}" method="POST">
                                       @csrf
                                       @method('DELETE')

                                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                           Cancel
                                       </button>

                                       <button type="submit" class="btn btn-danger">
                                           Yes, Delete
                                       </button>
                                   </form>
                               </div>

                           </div>
                       </div>
                   </div>
               @endcan

               @php $i++; @endphp
           @endforeach
       </tbody>
   </table>

   @include('admin._pagination', ['data' => $enquiries])
