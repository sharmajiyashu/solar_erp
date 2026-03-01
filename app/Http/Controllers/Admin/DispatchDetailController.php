<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DispatchDetail;
use Illuminate\Http\Request;

class DispatchDetailController extends Controller
{
    public function storeOrUpdate(Request $request, $leadId)
    {
        $request->validate([
            'transporter_name' => 'nullable',
            'vehicle_number' => 'nullable',
            'driver_contact' => 'nullable',
            'dispatch_date' => 'nullable|date',
            'status' => 'required'
        ]);

        DispatchDetail::updateOrCreate(

            [
                'lead_id' => $leadId
            ],

            [
                'transporter_name' => $request->transporter_name,
                'vehicle_number' => $request->vehicle_number,
                'driver_contact' => $request->driver_contact,
                'dispatch_date' => $request->dispatch_date,
                'status' => $request->status
            ]

        );

        return back()->with('success', 'Dispatch Details Saved');
    }
}
