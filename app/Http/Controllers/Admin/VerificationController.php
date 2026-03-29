<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationReport;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function store(Request $request, $leadId)
    {
        VerificationReport::updateOrCreate(

            [
                'lead_id' => $leadId
            ],

            [
                'verified_by' => $request->verified_by,
                'verified_by_manual' => $request->verified_by_manual,
                'verification_date' => $request->verification_date,
                'is_docs_proceed_for_2nd_tranch' => $request->has('is_docs_proceed_for_2nd_tranch'),
                'second_tier_payment_received' => $request->has('second_tier_payment_received'),
                'is_subsidy_received' => $request->has('is_subsidy_received'),
                'is_verified' => $request->has('is_verified'),
                'quotation_price' => $request->quotation_price,
                'first_tranche_date' => $request->first_tranche_date,
                'second_tranche_amount' => $request->second_tranche_amount,
                'tax_invoice_amount' => $request->tax_invoice_amount,
                'payout_amount' => $request->payout_amount,
                'status' => $request->has('is_verified') ? 'verified' : 'pending',
                'remarks' => $request->remarks
            ]
        );

        // Sync payment switches to the lead model
        $lead = \App\Models\Lead::findOrFail($leadId);
        $lead->update([
            'token_received' => $request->has('token_received'),
            'token_amount' => $request->token_amount,
            'first_payment_received' => $request->has('first_payment_received'),
            'first_tranche_amount' => $request->first_tranche_amount,
        ]);

        return back()->with('success', 'Verification Saved Successfully');
    }
}
