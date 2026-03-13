<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    public function updateTracking(Request $request, $leadId)
    {
        $this->authorize('leads edit');
        $lead = Lead::findOrFail($leadId);

        $lead->update([
            'first_payment_received' => $request->has('first_payment_received'),
        ]);

        return back()->with('success', 'Backend status updated.');
    }

    public function moveToProcurement($leadId)
    {
        $this->authorize('leads move-stage');
        $lead = Lead::findOrFail($leadId);

        if (!$lead->first_payment_received) {
            return back()->with('error', 'First payment must be received before moving to procurement.');
        }

        $stages = $lead->project_stages;
        if (is_string($stages)) {
            $stages = json_decode($stages, true);
        }
        $stages['backend']['status'] = 'done';
        $stages['backend']['completed_at'] = now();

        $lead->update([
            'stage' => 'dispatch',
            'status' => 'in_progress',
            'project_stages' => $stages
        ]);

        return back()->with('success', 'Lead moved to Procurement (Dispatch) stage.');
    }
}
