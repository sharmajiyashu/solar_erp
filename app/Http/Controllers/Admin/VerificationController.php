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
                'is_verified' => $request->has('is_verified'),
                'status' => $request->has('is_verified') ? 'verified' : 'pending',
                'remarks' => $request->remarks
            ]

        );

        return back()->with('success', 'Verification Saved Successfully');
    }
}
