<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use Illuminate\Http\Request;

class InstallationController extends Controller
{
    public function store(Request $request, $leadId)
    {
        $request->validate([
            'technician_id' => 'required',
            'installation_date' => 'nullable|date',
            'status' => 'required'
        ]);

        Installation::updateOrCreate(

            [
                'lead_id' => $leadId
            ],

            [
                'user_id' => $request->technician_id,
                'installation_date' => $request->installation_date,
                'status' => $request->status,
                'notes' => $request->notes
            ]

        );

        return back()->with('success', 'Installation Details Saved');
    }
}
