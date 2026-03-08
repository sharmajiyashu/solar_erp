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
                            <h2 class="content-header-title float-start mb-0">
                                Leads
                            </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Lead List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-company-table">

                                <!-- Search + Stage Filter -->
                                <div class="card-header">
                                    <div class="row w-100">
                                        <div class="col-md-4">
                                            <input type="text" id="searchInput" class="form-control"
                                                placeholder="Search by name, mobile, lead no">
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive" id="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Stage</th>
                                                <th>Status</th>
                                                <th>Assigned To</th>
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
                                                        {{ $item->assignedUser->name ?? '-' }}
                                                    </td>

                                                    <!-- Created -->
                                                    <td>
                                                        {{ $item->created_at->format('d-m-Y h:i A') }}
                                                    </td>

                                                    <!-- Actions -->
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                                data-bs-toggle="dropdown">
                                                                Action
                                                            </button>

                                                            <div class="dropdown-menu dropdown-menu-end">

                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.leads.show', $item->id) }}">
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
                                                                        $currentIndex = array_search(
                                                                            $item->stage,
                                                                            $stages,
                                                                        );
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

            $('#searchInput, #stageFilter').on('input change', function() {
                fetch_data();
            });

            function fetch_data() {
                $.ajax({
                    url: "?page=1",
                    method: 'GET',
                    data: {
                        search: $('#searchInput').val(),
                        stage: $('#stageFilter').val()
                    },
                    success: function(data) {
                        $('#table-responsive').html(data);
                    }
                });
            }

        });
    </script>
@endsection
