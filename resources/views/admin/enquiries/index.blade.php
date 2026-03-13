@extends('admin.layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>

        <div class="content-wrapper container-xxl p-0">

            <!-- Header -->
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Enquiries</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Enquiry List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                @can('enquiries create')
                    <div class="col-md-3 text-end">
                        <a href="{{ route('admin.enquiries.create') }}" class="btn btn-primary btn-gradient round">
                            Create Enquiry
                        </a>
                    </div>
                @endcan
            </div>

            <div class="content-body">

                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-company-table">

                                <!-- Search + Status Filter -->
                                <div class="card-header">
                                    <div class="row w-100">
                                        <div class="col-md-4">
                                            <input type="text" id="searchInput" class="form-control"
                                                placeholder="Search by name, mobile, enquiry no">
                                        </div>

                                        <div class="col-md-3">
                                            <select id="statusFilter" class="form-control">
                                                <option value="">All Status</option>
                                                <option value="pending">Pending</option>
                                                <option value="next_followup">Next Followup</option>
                                                <option value="converted_to_lead">Converted</option>
                                                <option value="mark_to_close">Mark to Close</option>
                                                <option value="closed">Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive" id="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Customer Details</th>
                                                <th>City / Address</th>
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
                                                        <div class="fw-bolder">{{ $item->customer_name }}</div>
                                                        <div class="small">M: {{ $item->mobile }}</div>
                                                        @if($item->alternate_mobile)
                                                            <div class="small text-muted">Alt: {{ $item->alternate_mobile }}</div>
                                                        @endif
                                                        <div class="small text-primary">{{ $item->enquiry_no }}</div>
                                                    </td>

                                                    <td>
                                                        <div>{{ $item->city ?? '-' }}</div>
                                                        <div class="small text-muted" title="{{ $item->address }}">
                                                            {{ Str::limit($item->address, 30) }}
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
                                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                                data-bs-toggle="dropdown">
                                                                Action
                                                            </button>

                                                            <div class="dropdown-menu dropdown-menu-end">

                                                                {{-- VIEW --}}
                                                                @can('enquiries view')
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.enquiries.show', $item->id) }}">
                                                                        View / Followups
                                                                    </a>
                                                                @endcan

                                                                {{-- EDIT --}}
                                                                @can('enquiries create')
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.enquiries.edit', $item->id) }}">
                                                                        Edit
                                                                    </a>
                                                                @endcan

                                                                {{-- CONVERT --}}
                                                                @can('enquiries create')
                                                                    @if ($item->status != 'converted_to_lead')
                                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" 
                                                                           data-bs-target="#convertModal{{ $item->id }}">
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
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('admin.enquiries.close', $item->id) }}">
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
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    Are you sure you want to delete
                                                                    <strong>{{ $item->customer_name }}</strong>?
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <form
                                                                        action="{{ route('admin.enquiries.destroy', $item->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">
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

                                                {{-- CONVERT MODAL --}}
                                                @include('admin.enquiries.partials.convert_modal', ['item' => $item])
                                            @endforeach
                                        </tbody>
                                    </table>

                                    @include('admin._pagination', ['data' => $enquiries])
                                </div>

                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    <!-- AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#searchInput, #statusFilter').on('input change', function() {
                fetch_data();
            });

            function fetch_data() {
                $.ajax({
                    url: "?page=1",
                    method: 'GET',
                    data: {
                        search: $('#searchInput').val(),
                        change_status: $('#statusFilter').val()
                    },
                    success: function(data) {
                        $('#table-responsive').html(data);
                    }
                });
            }

        });
    </script>
@endsection
