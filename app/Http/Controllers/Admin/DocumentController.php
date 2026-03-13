<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankDocument;
use Illuminate\Http\Request;

class DocumentController extends Controller
{


    public function store(Request $request, $leadId)
    {
        $request->validate([
            'document_type' => 'required',
            'file' => 'required|file|mimes:pdf,jpg,png|max:5120',
        ]);

        // Create uploads folder if not exists
        $destinationPath = public_path('uploads/bank_documents');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Generate Unique File Name
        $file = $request->file('file');

        $fileName = time() . '_' . $file->getClientOriginalName();

        // Move file to public/uploads/bank_documents
        $file->move($destinationPath, $fileName);

        BankDocument::create([
            'lead_id' => $leadId,
            'document_type' => $request->document_type,
            'file_path' => 'uploads/bank_documents/' . $fileName,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Document Uploaded Successfully');
    }

    public function destroy($id)
    {
        $doc = BankDocument::findOrFail($id);

        // Delete file from folder
        if (file_exists(public_path($doc->file_path))) {
            unlink(public_path($doc->file_path));
        }

        $doc->delete();

        return back()->with('success', 'Document Deleted Successfully');
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $doc = BankDocument::findOrFail($id);

        $doc->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status Updated');
    }

    public function updateLeadStatus(Request $request, $leadId)
    {
        $this->authorize('leads edit');
        $lead = \App\Models\Lead::findOrFail($leadId);

        $lead->update([
            'first_payment_received' => $request->has('first_payment_received'),
            'is_document_done' => $request->has('is_document_done'),
        ]);

        // Auto move to backend if both are checked
        if ($lead->first_payment_received && $lead->is_document_done && $lead->stage == 'document') {
            
            $stages = $lead->project_stages;
            $stages['document']['status'] = 'done';
            $stages['document']['completed_at'] = now();

            $lead->update([
                'stage' => 'backend',
                'status' => 'in_progress',
                'project_stages' => $stages
            ]);

            return back()->with('success', 'Status updated and moved to Backend stage.');
        }

        return back()->with('success', 'Status updated successfully.');
    }
}
