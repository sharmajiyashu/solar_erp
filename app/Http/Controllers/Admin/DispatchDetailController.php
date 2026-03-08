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
            'challan_book' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'status' => 'required'
        ]);

        $data = [
            'transporter_name' => $request->transporter_name,
            'vehicle_number' => $request->vehicle_number,
            'driver_contact' => $request->driver_contact,
            'dispatch_date' => $request->dispatch_date,
            'status' => $request->status
        ];

        // FILE UPLOAD
        if ($request->hasFile('challan_book')) {

            $file = $request->file('challan_book');

            $destinationPath = public_path('uploads/challan_books');

            // folder create if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move($destinationPath, $fileName);

            $data['challan_book'] = 'uploads/challan_books/' . $fileName;
        }

        DispatchDetail::updateOrCreate(
            [
                'lead_id' => $leadId
            ],
            $data
        );

        return back()->with('success', 'Dispatch Details Saved');
    }
}
