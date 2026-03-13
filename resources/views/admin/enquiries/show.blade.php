@extends('admin.layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">

                {{-- ================= ENQUIRY DETAILS ================= --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Enquiry Details</h4>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered">

                            <tr>
                                <th width="25%">Enquiry No</th>
                                <td>{{ $enquiry->enquiry_no }}</td>
                            </tr>

                            <tr>
                                <th>Customer Name</th>
                                <td>{{ $enquiry->customer_name }}</td>
                            </tr>

                            <tr>
                                <th>Mobile</th>
                                <td>{{ $enquiry->mobile }}</td>
                            </tr>

                            <tr>
                                <th>Alternate Mobile</th>
                                <td>{{ $enquiry->alternate_mobile ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>{{ $enquiry->email ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Address</th>
                                <td>{{ $enquiry->address ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>City / State</th>
                                <td>
                                    {{ $enquiry->city ?? '-' }},
                                    {{ $enquiry->state ?? '-' }}
                                </td>
                            </tr>

                            <tr>
                                <th>Pincode</th>
                                <td>{{ $enquiry->pincode ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Source</th>
                                <td>{{ $enquiry->source ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Main Remarks</th>
                                <td>{{ $enquiry->remarks ?? '-' }}</td>
                            </tr>

                            {{-- STATUS --}}
                            <tr>
                                <th>Status</th>
                                <td>
                                    @switch($enquiry->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @break

                                        @case('next_followup')
                                            <span class="badge bg-info">Next Followup</span>
                                        @break

                                        @case('converted_to_lead')
                                            <span class="badge bg-success">Converted To Lead</span>
                                        @break

                                        @case('closed')
                                            <span class="badge bg-danger">Closed</span>
                                        @break

                                        @case('mark_to_close')
                                            <span class="badge bg-secondary">Mark To Close</span>
                                        @break
                                    @endswitch
                                </td>
                            </tr>

                            <tr>
                                <th>Next Followup Date</th>
                                <td>
                                    {{ $enquiry->next_followup_date ? \Carbon\Carbon::parse($enquiry->next_followup_date)->format('d-m-Y') : '-' }}
                                </td>
                            </tr>

                            <tr>
                                <th>Created By</th>
                                <td>{{ $enquiry->creator->name ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Created At</th>
                                <td>{{ $enquiry->created_at->format('d-m-Y h:i A') }}</td>
                            </tr>

                        </table>

                        {{-- ACTION BUTTONS --}}
                        <div class="mt-2">

                            @can('enquiries create')
                                @if ($enquiry->status != 'converted_to_lead')
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#convertModal{{ $enquiry->id }}">
                                        Convert To Lead
                                    </button>
                                @endif
                            @endcan

                            @can('enquiries mark_to_close')
                                @if (!in_array($enquiry->status, ['closed', 'converted_to_lead', 'mark_to_close']))
                                    <a href="{{ route('admin.enquiries.markToClose', $enquiry->id) }}"
                                        class="btn btn-secondary btn-sm">
                                        Mark To Close
                                    </a>
                                @endif
                            @endcan

                            @can('enquiries close')
                                @if ($enquiry->status == 'mark_to_close')
                                    <a href="{{ route('admin.enquiries.close', $enquiry->id) }}" class="btn btn-danger btn-sm">
                                        Final Close
                                    </a>
                                @endif
                            @endcan

                        </div>
                    </div>
                </div>


                {{-- ================= ADD FOLLOWUP ================= --}}
                @if (!in_array($enquiry->status, ['closed', 'converted_to_lead']))
                    <div class="modal fade" id="addFollowupModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <form action="{{ route('admin.enquiries.storeFollowup', $enquiry->id) }}" method="POST">
                                    @csrf

                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Followup</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-md-4 mb-1">
                                                <label>Status</label>
                                                <select name="status" id="followup_status" class="form-control" onchange="toggleFollowupDate()">
                                                    <option value="pending">Pending</option>
                                                    <option value="completed">Completed</option>
                                                    <option value="rescheduled">Rescheduled</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4 mb-1" id="next_followup_date_row">
                                                <label>Next Followup Date</label>
                                                <input type="date" name="next_followup_date" id="next_followup_date" class="form-control">
                                            </div>

                                            <div class="col-md-12 mb-1">
                                                <label>Remarks</label>
                                                <textarea name="remarks" class="form-control" placeholder="Enter followup discussion" required></textarea>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <button type="submit" class="btn btn-primary">
                                            Save Followup
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                @endif


                {{-- ================= FOLLOWUP HISTORY ================= --}}
                <div class="card mt-2">
                    <div class="card-header">
                        <h5>Followup History</h5>

                        @if (!in_array($enquiry->status, ['closed', 'converted_to_lead']))
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addFollowupModal">
                                Add Followup
                            </button>
                        @endif

                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Followup Date</th>
                                    <th>Next Followup</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($enquiry->followUps as $key => $follow)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $follow->followup_date->format('d-m-Y') }}</td>
                                        <td>{{ $follow->next_followup_date ? \Carbon\Carbon::parse($follow->next_followup_date)->format('d-m-Y') : '-' }}</td>

                                        <td>
                                            @switch($follow->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @break

                                                @case('completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @break

                                                @case('rescheduled')
                                                    <span class="badge bg-info">Rescheduled</span>
                                                @break
                                            @endswitch
                                        </td>

                                        <td>{{ $follow->remarks }}</td>
                                        <td>{{ $follow->creator->name ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info edit-followup-btn" 
                                                data-id="{{ $follow->id }}">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                No Followups Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- CONVERT MODAL --}}
                @include('admin.enquiries.partials.convert_modal', ['item' => $enquiry])


                {{-- ================= EDIT FOLLOWUP MODAL ================= --}}
                <div class="modal fade" id="editFollowupModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <form id="editFollowupForm" method="POST">
                                @csrf

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Followup</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">

                                        <div class="col-md-4 mb-1">
                                            <label>Status</label>
                                            <select name="status" id="edit_followup_status" class="form-control" onchange="toggleEditFollowupDate()">
                                                <option value="pending">Pending</option>
                                                <option value="completed">Completed</option>
                                                <option value="rescheduled">Rescheduled</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-1" id="edit_next_followup_date_row">
                                            <label>Next Followup Date</label>
                                            <input type="date" name="next_followup_date" id="edit_next_followup_date" class="form-control">
                                        </div>

                                        <div class="col-md-12 mb-1">
                                            <label>Remarks</label>
                                            <textarea name="remarks" id="edit_followup_remarks" class="form-control" placeholder="Enter followup discussion" required></textarea>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Cancel
                                    </button>

                                    <button type="submit" class="btn btn-primary">
                                        Update Followup
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        @push('scripts')
        <script>
            function toggleFollowupDate() {
                var status = $('#followup_status').val();
                if (status === 'completed') {
                    $('#next_followup_date_row').hide();
                    $('#next_followup_date').prop('required', false);
                } else {
                    $('#next_followup_date_row').show();
                    $('#next_followup_date').prop('required', true);
                }
            }

            // Inital check
            $(document).ready(function() {
                toggleFollowupDate();

                $('.edit-followup-btn').on('click', function() {
                    var id = $(this).data('id');
                    var url = "{{ route('admin.enquiries.followup.edit', ':id') }}";
                    url = url.replace(':id', id);

                    $.get(url, function(data) {
                        $('#editFollowupForm').attr('action', "{{ route('admin.enquiries.followup.update', ':id') }}".replace(':id', id));
                        $('#edit_followup_status').val(data.status);
                        
                        if(data.next_followup_date) {
                             // Format date for input[type="date"]
                             var date = new Date(data.next_followup_date);
                             var day = ("0" + date.getDate()).slice(-2);
                             var month = ("0" + (date.getMonth() + 1)).slice(-2);
                             var formattedDate = date.getFullYear() + "-" + (month) + "-" + (day);
                             $('#edit_next_followup_date').val(formattedDate);
                        } else {
                             $('#edit_next_followup_date').val('');
                        }

                        $('#edit_followup_remarks').val(data.remarks);
                        
                        toggleEditFollowupDate();
                        $('#editFollowupModal').modal('show');
                    });
                });
            });

            function toggleEditFollowupDate() {
                var status = $('#edit_followup_status').val();
                if (status === 'completed') {
                    $('#edit_next_followup_date_row').hide();
                    $('#edit_next_followup_date').prop('required', false);
                } else {
                    $('#edit_next_followup_date_row').show();
                    $('#edit_next_followup_date').prop('required', true);
                }
            }
        </script>
    @endpush
@endsection
