@extends('admin.layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">

                {{-- ================= ENQUIRY DETAILS ================= --}}
                <div class="card">
                    <div class="card-header bg-primary text-white">
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

                            @can('enquiries convert_to_lead')
                                @if ($enquiry->status != 'converted_to_lead')
                                    <a href="{{ route('admin.enquiries.convert', $enquiry->id) }}"
                                        class="btn btn-success btn-sm">
                                        Convert To Lead
                                    </a>
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
                    <div class="card mt-2">
                        <div class="card-header">
                            <h5>Add Followup</h5>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.enquiries.storeFollowup', $enquiry->id) }}" method="POST">
                                @csrf

                                <div class="row">

                                    <div class="col-md-3">
                                        <label>Next Followup Date</label>
                                        <input type="date" name="next_followup_date" class="form-control" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="pending">Pending</option>
                                            <option value="completed">Completed</option>
                                            <option value="rescheduled">Rescheduled</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Remarks</label>
                                        <textarea name="remarks" class="form-control" required></textarea>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            Save
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                @endif


                {{-- ================= FOLLOWUP HISTORY ================= --}}
                <div class="card mt-2">
                    <div class="card-header bg-dark text-white">
                        <h5>Followup History</h5>
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
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($enquiry->followUps as $key => $follow)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $follow->followup_date->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($follow->next_followup_date)->format('d-m-Y') }}</td>

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
            </div>
        </div>
    @endsection
