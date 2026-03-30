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
                        <h2 class="content-header-title float-start mb-0">Payment Analysis Report</h2>
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
                    <a href="{{ route('admin.reports.payment_analysis.export', request()->all()) }}" class="btn btn-primary">
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
                    <form action="{{ route('admin.reports.payment_analysis') }}" method="GET">
                        <div class="row">
                            <div class="col-md-8 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="search">Search</label>
                                    <input type="text" id="search" name="search" class="form-control" placeholder="Enquiry No, Name, Mobile" value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-12 d-flex align-items-end">
                                <div class="mb-1">
                                    <button type="submit" class="btn btn-outline-primary me-1">Filter</button>
                                    <a href="{{ route('admin.reports.payment_analysis') }}" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover" style="font-size: 0.85rem;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer / Mobile</th>
                                <th>KW</th>
                                <th>Loan/Cash</th>
                                <th>Quotation</th>
                                <th>Token</th>
                                <th>1st Tr.</th>
                                <th>2nd Tr.</th>
                                <th>Total Rec.</th>
                                <th class="text-danger">Pending</th>
                                <th>Net Saving</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enquiries as $enquiry)
                            @php
                                $lead = $enquiry->lead;
                                $vReport = $lead ? $lead->verificationReport : null;
                                
                                $token = $lead ? ($lead->token_amount ?? 0) : 0;
                                $tranch1 = $lead ? ($lead->first_tranche_amount ?? 0) : 0;
                                $tranch2 = $vReport ? ($vReport->second_tranche_amount ?? 0) : 0;
                                $totalRec = $token + $tranch1 + $tranch2;
                                
                                $quotation = $vReport ? ($vReport->quotation_price ?? 0) : 0;
                                $proforma = $lead ? $lead->procurementItems->sum('total') : 0;
                                $taxInvoice = $vReport ? ($vReport->tax_invoice_amount ?? 0) : 0;
                                $payout = $vReport ? ($vReport->payout_amount ?? 0) : 0;
                                
                                $dividedBit = ($taxInvoice - $quotation) * 0.15;
                                $netSaving = $quotation - ($proforma + $payout + $dividedBit);
                                $pending = $quotation - $totalRec;
                            @endphp
                            <tr>
                                <td>{{ $enquiry->created_at->format('d/m/y') }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="user-name fw-bolder text-truncate">{{ $enquiry->customer_name }}</span>
                                        <small class="text-muted text-truncate">{{ $enquiry->mobile }}</small>
                                    </div>
                                </td>
                                <td>{{ $enquiry->project_size }}</td>
                                <td>{{ $lead->lead_type ?? 'N/A' }}</td>
                                <td>₹{{ number_format($quotation, 2) }}</td>
                                <td>₹{{ number_format($token, 2) }}</td>
                                <td>₹{{ number_format($tranch1, 2) }}</td>
                                <td>₹{{ number_format($tranch2, 2) }}</td>
                                <td class="fw-bold">₹{{ number_format($totalRec, 2) }}</td>
                                <td class="text-danger fw-bold">₹{{ number_format($pending, 2) }}</td>
                                <td class="text-success fw-bold">₹{{ number_format($netSaving, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">No records found.</td>
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
