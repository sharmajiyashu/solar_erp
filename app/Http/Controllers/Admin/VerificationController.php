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
            'verified_by' => 'required',
            'status' => 'required'
        ]);

        VerificationReport::updateOrCreate(

            [
                'lead_id' => $leadId
            ],

            [
                'verified_by' => $request->verified_by,
                'verification_date' => $request->verification_date,
                'status' => $request->status,
                'remarks' => $request->remarks
            ]

        );

        return back()->with('success', 'Verification Saved Successfully');
    }
}
