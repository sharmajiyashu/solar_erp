<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationReport;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function store(Request $request, $leadId)
    {
        $request->validate([
            'status' => 'required'
        ]);

        VerificationReport::updateOrCreate(

            [
                'lead_id' => $leadId
            ],

            [
                'verified_by' => $request->verified_by,
                'verified_by_manual' => $request->verified_by_manual,
                'verification_date' => $request->verification_date,
                'status' => $request->status,
                'second_tier_payment_received' => $request->has('second_tier_payment_received'),
                'remarks' => $request->remarks
            ]

        );

        return back()->with('success', 'Verification Saved Successfully');
    }
}
