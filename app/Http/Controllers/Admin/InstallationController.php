<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use App\Models\InstallationAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstallationController extends Controller
{
    public function store(Request $request, $leadId)
    {
        $request->validate([
            'installation_date' => 'nullable|date',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf'
        ]);

        // Installation Create / Update
        $installation = Installation::updateOrCreate(

            [
                'lead_id' => $leadId
            ],

            [
                'user_id' => Auth::id(),
                'installation_date' => $request->installation_date,
                'status' => 'pending',
                'notes' => $request->notes,
                'installation_done' => $request->has('installation_done'),
                'net_metering_done' => $request->has('net_metering_done'),
            ]

        );

        // Multiple Attachments Upload
        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {

                $destinationPath = public_path('uploads/installations');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();

                $file->move($destinationPath, $fileName);

                // Save in DB
                $installation->attachments()->create([
                    'file' => 'uploads/installations/' . $fileName
                ]);
            }
        }

        if ($installation->installation_done && $installation->net_metering_done) {
            $lead = \App\Models\Lead::find($leadId);
            if ($lead && $lead->stage == 'installation') {
                $stages = $lead->project_stages;
                if (is_string($stages)) {
                    $stages = json_decode($stages, true);
                }
                $stages['installation']['status'] = 'done';
                $stages['installation']['completed_at'] = now();

                $lead->update([
                    'stage' => 'verification',
                    'status' => 'pending',
                    'project_stages' => $stages
                ]);

                return back()->with('success', 'Installation Details Saved and Lead moved to Verification stage.');
            }
        }

        return back()->with('success', 'Installation Details Saved');
    }


    public function deleteAttachment($id)
    {
        $attachment = InstallationAttachment::findOrFail($id);

        $filePath = public_path($attachment->file);

        // delete file from folder
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // delete record
        $attachment->delete();

        return back()->with('success', 'Attachment Deleted');
    }
}
