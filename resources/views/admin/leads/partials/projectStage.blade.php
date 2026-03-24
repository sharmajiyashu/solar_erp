@php
    $stages = $lead->project_stages ?? [];

    if (!is_array($stages)) {
        $stages = json_decode($stages, true) ?? [];
    }
@endphp

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Stage</th>
            <th>Status</th>
            <th>Completed At</th>
        </tr>
    </thead>

    <tbody>

        @foreach (\App\Models\Lead::$workflowStages as $stageKey)
            @php
                $stageData = $stages[$stageKey] ?? [
                    'status' => 'pending',
                    'completed_at' => null,
                ];

                // Sync Document Stage with First Transaction status
                if ($stageKey == 'document') {
                    if ($lead->first_payment_received && $lead->is_document_done) {
                        $stageData['status'] = 'done';
                    }
                }

                // Sync Backend Stage
                if ($stageKey == 'backend') {
                    $isBankRequired = $lead->lead_type == 'loan';
                    if ($lead->discom_pms_portal_login_done && (!$isBankRequired || $lead->bank_login_done) && $lead->first_payment_received) {
                        $stageData['status'] = 'done';
                    }
                }

                // Sync Verification Stage with 2nd Tranch and Verification status
                if ($stageKey == 'verification') {
                    $report = $lead->verificationReport;
                    if ($report && $report->is_docs_proceed_for_2nd_tranch && $report->second_tier_payment_received && $report->is_subsidy_received && $report->is_verified) {
                        $stageData['status'] = 'done';
                    }
                }

                // Sync Installation Stage
                if ($stageKey == 'installation') {
                    $inst = $lead->installation;
                    if ($inst && $inst->installation_done && $inst->net_metering_done) {
                        $stageData['status'] = 'done';
                    }
                }
            @endphp

            <tr>
                <td>
                    {{ ucfirst(str_replace('_', ' ', $stageKey)) }}
                    
                    @if($stageKey == 'document')
                        <div class="mt-1 flex-wrap d-flex gap-1 text-nowrap">
                            <span class="badge {{ $lead->token_received ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">Token Received</span>
                            <span class="badge {{ $lead->is_document_done ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">Documents Received</span>
                            <span class="badge bg-secondary py-0 px-1" style="font-size: 1rem;">{{ ucfirst($lead->lead_type) }}</span>
                        </div>
                    @elseif($stageKey == 'backend')
                        <div class="mt-1 flex-wrap d-flex gap-1 text-nowrap">
                            <span class="badge {{ $lead->discom_pms_portal_login_done ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">Portal Login</span>
                            @if($lead->lead_type == 'loan')
                                <span class="badge {{ $lead->bank_login_done ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">Bank Login</span>
                            @endif
                            <span class="badge {{ $lead->first_payment_received ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">First Tranche Received</span>
                        </div>
                    @elseif($stageKey == 'installation')
                        @php $inst = $lead->installation; @endphp
                        <div class="mt-1 flex-wrap d-flex gap-1 text-nowrap">
                            <span class="badge {{ optional($inst)->installation_done ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">Installation Done</span>
                            <span class="badge {{ optional($inst)->net_metering_done ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">Net Metering Done</span>
                        </div>
                    @elseif($stageKey == 'verification')
                        @php $vr = $lead->verificationReport; @endphp
                        <div class="mt-1 flex-wrap d-flex gap-1 text-nowrap">
                            <span class="badge {{ optional($vr)->is_docs_proceed_for_2nd_tranch ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">Docs for 2nd Tranch</span>
                            <span class="badge {{ optional($vr)->second_tier_payment_received ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">2nd Tranch Received</span>
                            <span class="badge {{ optional($vr)->is_subsidy_received ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">Subsidy Received</span>
                            <span class="badge {{ optional($vr)->is_verified ? 'bg-success' : 'bg-danger' }} py-0 px-1" style="font-size: 1rem;">Verified</span>
                        </div>
                    @endif
                </td>

                <td>
                    @if ($stageData['status'] == 'done')
                        <span class="badge bg-success">Done</span>
                    @else
                        <span class="badge bg-warning">
                            {{ ucfirst($stageData['status']) }}
                        </span>
                    @endif
                </td>

                <td>
                    {{ $stageData['completed_at'] ? \Carbon\Carbon::parse($stageData['completed_at'])->format('d-m-Y h:i A') : '-' }}
                </td>
            </tr>
        @endforeach

    </tbody>
</table>
