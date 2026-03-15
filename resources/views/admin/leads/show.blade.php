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

                    </table>

                    @php
                        $stageTabMap = [
                            'site_visit'   => ['tab' => 'visitTab',           'permission' => 'site_visits schedule'],
                            'quotation'    => ['tab' => 'quotationTab',       'permission' => 'quotations view'],
                            'document'     => ['tab' => 'documentTab',        'permission' => 'document_management view'],
                            'backend'      => ['tab' => 'backendTab',         'permission' => 'backend_management view'],
                            'procurement'  => ['tab' => 'dispatchDetailTab',  'permission' => 'procurement_management view'],
                            'installation' => ['tab' => 'installationTab',    'permission' => 'installation_management view'],
                            'verification' => ['tab' => 'verificationTab',    'permission' => 'verification_management view'],
                            'completed'    => ['tab' => 'completedTab',       'permission' => 'project_completion view'],
                        ];

                        $activeTab = 'leadTab'; // Default back to lead details
                        if (isset($stageTabMap[$lead->stage]) && Auth::user()->can($stageTabMap[$lead->stage]['permission'])) {
                            $activeTab = $stageTabMap[$lead->stage]['tab'];
                        }

                        $stages_list = [
                            'site_visit',
                            'quotation',
                            'document',
                            'backend',
                            'procurement',
                            'installation',
                            'verification',
                            'completed',
                        ];
                        $leadStageIndex = array_search($lead->stage, $stages_list);
                    @endphp

                    <div class="card-header">
                        <ul class="nav nav-tabs" id="leadTabs">

                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'projectStage' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#projectStage">
                                    Stage
                                </button>
                            </li>

                            @can('leads view')
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'leadTab' ? 'active' : '' }}" data-bs-toggle="tab"
                                    data-bs-target="#leadTab">
                                    Lead Details
                                </button>
                            </li>
                            @endcan

                            @can('site_visits schedule')
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'visitTab' ? 'active' : '' }}" data-bs-toggle="tab"
                                    data-bs-target="#visitTab">
                                    Visit Management
                                </button>
                            </li>
                            @endcan

                            @can('quotations view')
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'quotationTab' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#quotationTab">
                                    Quotation Management
                                </button>
                            </li>
                            @endcan

                            @can('document_management view')
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'documentTab' ? 'active' : '' }}" data-bs-toggle="tab"
                                    data-bs-target="#documentTab">
                                    Document
                                </button>
                            </li>
                            @endcan

                            @can('backend_management view')
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'backendTab' ? 'active' : '' }}" data-bs-toggle="tab"
                                    data-bs-target="#backendTab">
                                    Backend Management
                                </button>
                            </li>
                            @endcan

                            @can('procurement_management view')
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'dispatchDetailTab' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#dispatchDetailTab">
                                    Procurement Management
                                </button>
                            </li>
                            @endcan

                            @can('installation_management view')
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'installationTab' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#installationTab">
                                    Installation Management
                                </button>
                            </li>
                            @endcan

                            @can('verification_management view')
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'verificationTab' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#verificationTab">
                                    Verification Management
                                </button>
                            </li>
                            @endcan

                            @can('project_completion view')
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab == 'completedTab' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#completedTab">
                                    Project Completion
                                </button>
                            </li>
                            @endcan

                        </ul>
                    </div>

                    <div class="card-body">

                        <div class="tab-content">

                            <div class="tab-pane fade {{ $activeTab == 'projectStage' ? 'show active' : '' }}"
                                id="projectStage">
                                @include('admin.leads.partials.projectStage')
                            </div>

                            @can('leads view')
                            <div class="tab-pane fade {{ $activeTab == 'leadTab' ? 'show active' : '' }}" id="leadTab">
                                @include('admin.leads.partials.lead')
                                @include('admin.leads.partials.customer')
                            </div>
                            @endcan

                            @can('site_visits schedule')
                            <div class="tab-pane fade {{ $activeTab == 'visitTab' ? 'show active' : '' }}" id="visitTab">
                                @include('admin.leads.partials.visits')
                            </div>
                            @endcan

                            @can('quotations view')
                            <div class="tab-pane fade {{ $activeTab == 'quotationTab' ? 'show active' : '' }}"
                                id="quotationTab">
                                @include('admin.leads.partials.quotation')
                            </div>
                            @endcan

                            @can('document_management view')
                            <div class="tab-pane fade {{ $activeTab == 'documentTab' ? 'show active' : '' }}" id="documentTab">
                                @php $is_past_stage = $leadStageIndex > array_search('document', $stages_list); @endphp
                                @include('admin.leads.partials.document')
                            </div>
                            @endcan

                            @can('backend_management view')
                            <div class="tab-pane fade {{ $activeTab == 'backendTab' ? 'show active' : '' }}" id="backendTab">
                                @php $is_past_stage = $leadStageIndex > array_search('backend', $stages_list); @endphp
                                @include('admin.leads.partials.backend')
                            </div>
                            @endcan

                            @can('procurement_management view')
                            <div class="tab-pane fade {{ $activeTab == 'dispatchDetailTab' ? 'show active' : '' }}"
                                id="dispatchDetailTab">
                                @php $is_past_stage = $leadStageIndex > array_search('procurement', $stages_list); @endphp
                                @include('admin.leads.partials.dispatchDetail')
                            </div>
                            @endcan

                            @can('installation_management view')
                            <div class="tab-pane fade {{ $activeTab == 'installationTab' ? 'show active' : '' }}"
                                id="installationTab">
                                @php $is_past_stage = $leadStageIndex > array_search('installation', $stages_list); @endphp
                                @include('admin.leads.partials.installation')
                            </div>
                            @endcan

                            @can('verification_management view')
                            <div class="tab-pane fade {{ $activeTab == 'verificationTab' ? 'show active' : '' }}"
                                id="verificationTab">
                                @php $is_past_stage = $leadStageIndex > array_search('verification', $stages_list); @endphp
                                @include('admin.leads.partials.verification')
                            </div>
                            @endcan

                            @can('project_completion view')
                            <div class="tab-pane fade {{ $activeTab == 'completedTab' ? 'show active' : '' }}"
                                id="completedTab">
                                @include('admin.leads.partials.completed_form')
                                @include('admin.leads.partials.completion_photos')
                            </div>
                            @endcan

                        </div>

                    </div>




                </div>

            </div>
        </div>
    </div>
@endsection
