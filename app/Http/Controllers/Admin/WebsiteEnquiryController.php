<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteEnquiry;
use Illuminate\Http\Request;

class WebsiteEnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = WebsiteEnquiry::latest();

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status != '' && $request->status != 'all') {
            $query->where('status', $request->status);
        } elseif (!$request->has('status')) {
            $query->where('status', 'pending');
        }

        $enquiries = $query->paginate(20);

        return view('admin.website_enquiries.index', compact('enquiries'));
    }

    public function show($id)
    {
        $enquiry = WebsiteEnquiry::findOrFail($id);
        return view('admin.website_enquiries.show', compact('enquiry'));
    }

    public function destroy($id)
    {
        $enquiry = WebsiteEnquiry::findOrFail($id);
        $enquiry->delete();

        return redirect()->route('admin.website-enquiries.index')
            ->with('success', 'Enquiry deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $enquiry = WebsiteEnquiry::findOrFail($id);
        $enquiry->update(['status' => $request->status]);

        return response()->json(['status' => 'success', 'message' => 'Status updated successfully.']);
    }
}
