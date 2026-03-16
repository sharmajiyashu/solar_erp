@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Enquiry & Lead Report</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Reports</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1">
                    @can('reports export')
                    <a href="{{ route('admin.reports.export', request()->all()) }}" class="btn btn-primary">
                        <i data-feather="download"></i> Export CSV
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Filter Section -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="search">Search</label>
                                    <input type="text" id="search" name="search" class="form-control" placeholder="Enquiry No, Name, Mobile" value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="status">Enquiry Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="converted_to_lead" {{ request('status') == 'converted_to_lead' ? 'selected' : '' }}>Converted to Lead</option>
                                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                        <option value="mark_to_close" {{ request('status') == 'mark_to_close' ? 'selected' : '' }}>Mark to Close</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 d-flex align-items-end">
                                <div class="mb-1">
                                    <button type="submit" class="btn btn-outline-primary me-1">Filter</button>
                                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Enquiry No</th>
                                <th>Customer</th>
                                <th>Created By</th>
                                <th>Status</th>
                                <th>Lead Info</th>
                                <th class="text-center">Workflow & Milestones</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enquiries as $enquiry)
                            @php
                                $lead = $enquiry->lead;
                                $stages = $lead ? (is_string($lead->project_stages) ? json_decode($lead->project_stages, true) : $lead->project_stages) : [];
                                $workflow = ['site_visit', 'quotation', 'document', 'backend', 'procurement', 'installation', 'verification', 'completed'];
                                
                                $installation = $lead ? $lead->installation : null;
                                $vReport = $lead ? $lead->verificationReport : null;
                            @endphp
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $enquiry->enquiry_no }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="user-name fw-bolder text-truncate">{{ $enquiry->customer_name }}</span>
                                        <small class="text-muted text-truncate">{{ $enquiry->mobile }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light-secondary">{{ $enquiry->creator->name ?? 'System' }}</span>
                                </td>
                                <td>
                                    @if($enquiry->status == 'converted_to_lead')
                                        <span class="badge rounded-pill badge-light-success text-capitalize">Converted</span>
                                    @else
                                        <span class="badge rounded-pill badge-light-primary text-capitalize">{{ str_replace('_', ' ', $enquiry->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($lead)
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $lead->lead_no }}</span>
                                            <span class="badge badge-light-warning text-capitalize" style="font-size: 0.7rem;">{{ str_replace('_', ' ', $lead->stage) }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($lead)
                                    <div class="d-flex flex-column gap-1" style="min-width: 200px;">
                                        <!-- Workflow Progress Dots -->
                                        <div class="d-flex justify-content-between mb-25">
                                            @foreach($workflow as $wStage)
                                                @php
                                                    $sStatus = $stages[$wStage]['status'] ?? 'pending';
                                                    $color = $sStatus == 'done' ? 'success' : ($sStatus == 'in_progress' ? 'warning' : 'secondary');
                                                    $title = ucfirst(str_replace('_', ' ', $wStage)) . ': ' . ucfirst($sStatus);
                                                @endphp
                                                <div style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--bs-{{ $color }});" title="{{ $title }}"></div>
                                            @endforeach
                                        </div>

                                        <!-- Granular Milestones -->
                                        <div class="milestones-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 4px; font-size: 0.65rem;">
                                            <div class="d-flex align-items-center gap-25">
                                                <i data-feather="{{ $lead->discom_pms_portal_login_done ? 'check-circle' : 'circle' }}" class="text-{{ $lead->discom_pms_portal_login_done ? 'success' : 'muted' }}" style="width: 10px; height: 10px;"></i>
                                                <span>Discom</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-25">
                                                <i data-feather="{{ $lead->bank_login_done ? 'check-circle' : 'circle' }}" class="text-{{ $lead->bank_login_done ? 'success' : 'muted' }}" style="width: 10px; height: 10px;"></i>
                                                <span>Bank Log</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-25">
                                                <i data-feather="{{ $lead->first_payment_received ? 'check-circle' : 'circle' }}" class="text-{{ $lead->first_payment_received ? 'success' : 'muted' }}" style="width: 10px; height: 10px;"></i>
                                                <span>1st Pay</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-25">
                                                <i data-feather="{{ $lead->is_document_done ? 'check-circle' : 'circle' }}" class="text-{{ $lead->is_document_done ? 'success' : 'muted' }}" style="width: 10px; height: 10px;"></i>
                                                <span>Docs Done</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-25">
                                                <i data-feather="{{ $lead->handover_by ? 'check-circle' : 'circle' }}" class="text-{{ $lead->handover_by ? 'success' : 'muted' }}" style="width: 10px; height: 10px;"></i>
                                                <span>Handover</span>
                                            </div>

                                            @if($installation)
                                                <div class="d-flex align-items-center gap-25">
                                                    <i data-feather="{{ $installation->installation_done ? 'check-circle' : 'circle' }}" class="text-{{ $installation->installation_done ? 'success' : 'muted' }}" style="width: 10px; height: 10px;"></i>
                                                    <span>Inst Done</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-25">
                                                    <i data-feather="{{ $installation->net_metering_done ? 'check-circle' : 'circle' }}" class="text-{{ $installation->net_metering_done ? 'success' : 'muted' }}" style="width: 10px; height: 10px;"></i>
                                                    <span>Net Met</span>
                                                </div>
                                            @endif

                                            @if($vReport)
                                                <div class="d-flex align-items-center gap-25">
                                                    <i data-feather="{{ $vReport->is_docs_proceed_for_2nd_tranch ? 'check-circle' : 'circle' }}" class="text-{{ $vReport->is_docs_proceed_for_2nd_tranch ? 'success' : 'muted' }}" style="width: 10px; height: 10px;"></i>
                                                    <span>2nd Docs</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-25">
                                                    <i data-feather="{{ $vReport->second_tier_payment_received ? 'check-circle' : 'circle' }}" class="text-{{ $vReport->second_tier_payment_received ? 'success' : 'muted' }}" style="width: 10px; height: 10px;"></i>
                                                    <span>2nd Pay</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($lead->token_amount)
                                            <div class="mt-25" style="font-size: 0.65rem;">
                                                <span class="text-muted">Token:</span> <span class="fw-bold">₹{{ number_format($lead->token_amount) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    @else
                                        <div class="text-center text-muted">-</div>
                                    @endif
                                </td>
                                <td>{{ $enquiry->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No reports found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    @include('admin._pagination', ['data' => $enquiries])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
