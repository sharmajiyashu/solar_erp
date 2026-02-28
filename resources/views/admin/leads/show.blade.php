@extends('admin.layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper container-xxl p-0">

            <div class="content-body">


                <div class="card">


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
                            <th>Remarks</th>
                            <td>{{ $lead->remarks ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Created By</th>
                            <td>{{ $lead->creator->name ?? '-' }}</td>
                        </tr>





                    </table>



                    <div class="card-header">
                        <ul class="nav nav-tabs" id="leadTabs">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#customerTab">
                                    Customer Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#leadTab">
                                    Lead Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#visitTab">
                                    Visit Management
                                </button>
                            </li>

                        </ul>
                    </div>

                    <div class="card-body">

                        <div class="tab-content">

                            {{-- ================= CUSTOMER TAB ================= --}}
                            <div class="tab-pane fade show active" id="customerTab">
                                @include('admin.leads.partials.customer')
                            </div>

                            {{-- ================= LEAD TAB ================= --}}
                            <div class="tab-pane fade" id="leadTab">
                                @include('admin.leads.partials.lead')
                            </div>

                            {{-- ================= VISIT TAB ================= --}}
                            <div class="tab-pane fade" id="visitTab">
                                @include('admin.leads.partials.visits')
                            </div>

                        </div>

                    </div>


                    @if ($lead->stage != 'completed')
                        @php
                            $stages = [
                                'pending_lead',
                                'site_visit',
                                'quotation',
                                'bank',
                                'discom',
                                'dispatch',
                                'installation',
                                'verification',
                                'completed',
                            ];
                            $currentIndex = array_search($lead->stage, $stages);
                            $nextStage = $stages[$currentIndex + 1] ?? null;
                        @endphp


                        @if ($nextStage)
                            <div>
                                <a class="btn btn-success"
                                    href="{{ route('admin.leads.move_stage', [$lead->id, $nextStage]) }}">
                                    Move To
                                    {{ ucfirst(str_replace('_', ' ', $nextStage)) }}
                                </a>
                            </div>
                        @endif
                    @endif

                </div>



            </div>
        </div>
    </div>
@endsection
