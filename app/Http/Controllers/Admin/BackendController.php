<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    public function updateTracking(Request $request, $leadId)
    {
        $lead = Lead::findOrFail($leadId);

        $lead->update([
            'first_payment_received' => $request->has('first_payment_received'),
            'discom_pms_portal_login_done' => $request->has('discom_pms_portal_login_done'),
            'bank_login_done' => $request->has('bank_login_done'),
        ]);

        return back()->with('success', 'Backend status updated.');
    }

    public function moveToProcurement($leadId)
    {
        $lead = Lead::findOrFail($leadId);

        if (!$lead->first_payment_received || !$lead->discom_pms_portal_login_done || !$lead->bank_login_done) {
            return back()->with('error', 'All backend steps (Portal Login, Bank Login, First Payment) must be completed before moving to procurement.');
        }

        $stages = $lead->project_stages;
        if (is_string($stages)) {
            $stages = json_decode($stages, true);
        }
        $stages['backend']['status'] = 'done';
        $stages['backend']['completed_at'] = now();

        $lead->update([
            'stage' => 'procurement',
            'status' => 'in_progress',
            'project_stages' => $stages
        ]);

        return back()->with('success', 'Lead moved to Procurement (Dispatch) stage.');
    }
}
