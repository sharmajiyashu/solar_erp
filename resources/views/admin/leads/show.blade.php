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
                            <th>Change Stage</th>
                            <td>
                                @if ($lead->stage != 'completed')
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
                                        $currentIndex = array_search($lead->stage, $stages);
                                        $nextStage = $stages[$currentIndex + 1] ?? null;
                                    @endphp


                                    @if ($nextStage)
                                        @can('leads move-stage')
                                            <div>
                                                <a class=""
                                                    href="{{ route('admin.leads.move_stage', [$lead->id, $nextStage]) }}">
                                                    Move To
                                                    {{ ucfirst(str_replace('_', ' ', $nextStage)) }}
                                                </a>
                                            </div>
                                        @endcan
                                    @endif
                                @endif
                            </td>
                        </tr>


                    </table>

                    @php
                        $stageTabMap = [
                            'site_visit' => 'visitTab',
                            'quotation' => 'quotationTab',
                            'bank' => 'bankTab',
                            'dispatch' => 'dispatchDetailTab',
                            'installation' => 'installationTab',
                            'verification' => 'verificationTab',
                            'completed' => 'projectStage',
                        ];

                        $activeTab = $stageTabMap[$lead->stage] ?? 'projectStage';
                    @endphp

                    <div class="card-header">
                        <ul class="nav nav-tabs" id="leadTabs">

                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'projectStage' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#projectStage">
                                    Stage
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'leadTab' ? 'active' : '' }}" data-bs-toggle="tab"
                                    data-bs-target="#leadTab">
                                    Lead Details
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'visitTab' ? 'active' : '' }}" data-bs-toggle="tab"
                                    data-bs-target="#visitTab">
                                    Visit Management
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'quotationTab' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#quotationTab">
                                    Quotation Management
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'bankTab' ? 'active' : '' }}" data-bs-toggle="tab"
                                    data-bs-target="#bankTab">
                                    Document
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'dispatchDetailTab' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#dispatchDetailTab">
                                    Procure Management
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'installationTab' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#installationTab">
                                    Installation Management
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'verificationTab' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#verificationTab">
                                    Verification Management
                                </button>
                            </li>

                        </ul>
                    </div>

                    <div class="card-body">

                        <div class="tab-content">

                            <div class="tab-pane fade {{ $activeTab == 'projectStage' ? 'show active' : '' }}"
                                id="projectStage">
                                @include('admin.leads.partials.projectStage')
                            </div>

                            <div class="tab-pane fade {{ $activeTab == 'leadTab' ? 'show active' : '' }}" id="leadTab">
                                @include('admin.leads.partials.lead')
                                @include('admin.leads.partials.customer')
                            </div>

                            <div class="tab-pane fade {{ $activeTab == 'visitTab' ? 'show active' : '' }}" id="visitTab">
                                @include('admin.leads.partials.visits')
                            </div>

                            <div class="tab-pane fade {{ $activeTab == 'quotationTab' ? 'show active' : '' }}"
                                id="quotationTab">
                                @include('admin.leads.partials.quotation')
                            </div>

                            <div class="tab-pane fade {{ $activeTab == 'bankTab' ? 'show active' : '' }}" id="bankTab">
                                @include('admin.leads.partials.bank')
                            </div>

                            <div class="tab-pane fade {{ $activeTab == 'dispatchDetailTab' ? 'show active' : '' }}"
                                id="dispatchDetailTab">
                                @include('admin.leads.partials.dispatchDetail')
                            </div>

                            <div class="tab-pane fade {{ $activeTab == 'installationTab' ? 'show active' : '' }}"
                                id="installationTab">
                                @include('admin.leads.partials.installation')
                            </div>

                            <div class="tab-pane fade {{ $activeTab == 'verificationTab' ? 'show active' : '' }}"
                                id="verificationTab">
                                @include('admin.leads.partials.verification')
                            </div>

                        </div>

                    </div>




                </div>

            </div>
        </div>
    </div>
@endsection
