<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\EnquiryFollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query_search  = $request->input('search');
        $change_status = $request->input('change_status');

        $enquiries = Enquiry::with('creator')

            ->when(!Auth::user()->can('enquiries get-all'), function ($query) {
                $query->where('created_by', Auth::id());
            })

            ->when($query_search, function ($query) use ($query_search) {
                $query->where(function ($q) use ($query_search) {
                    $q->where('customer_name', 'like', '%' . $query_search . '%')
                        ->orWhere('mobile', 'like', '%' . $query_search . '%')
                        ->orWhere('enquiry_no', 'like', '%' . $query_search . '%');
                });
            })

            ->when($change_status !== null, function ($query) use ($change_status) {
                $query->where('status', $change_status);
            })

            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return view('admin.enquiries.pagination', compact('enquiries'))->render();
        }

        return view('admin.enquiries.index', compact('enquiries'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Enquiry Form
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.enquiries.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Enquiry
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string',
            'mobile'            => 'required|string',
            'alternate_mobile'  => 'nullable|string',
            'email'             => 'nullable|email',
            'address'           => 'nullable|string',
            'city'              => 'nullable|string',
            'state'             => 'nullable|string',
            'pincode'           => 'nullable|string',
            'source'            => 'nullable|string',
            'remarks'           => 'nullable|string',
            'next_followup_date' => 'nullable|date',
        ]);

        $validated['enquiry_no'] = 'ENQ-' . now()->timestamp;
        $validated['created_by'] = Auth::id();
        $validated['status']     = 'pending';

        Enquiry::create($validated);

        return redirect()->route('admin.enquiries.index')
            ->with('success', 'Enquiry created successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Enquiry
    |--------------------------------------------------------------------------
    */
    public function edit(string $id)
    {
        $enquiry = Enquiry::findOrFail($id);
        return view('admin.enquiries.create', compact('enquiry'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Enquiry
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'mobile'        => 'required|string',
            'email'         => 'nullable|email',
            'city'          => 'nullable|string',
            'state'         => 'nullable|string',
            'status'        => 'required'
        ]);

        Enquiry::where('id', $id)->update($validated);

        return redirect()->route('admin.enquiries.index')
            ->with('success', 'Enquiry updated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Enquiry
    |--------------------------------------------------------------------------
    */
    public function destroy(string $id)
    {
        Enquiry::where('id', $id)->delete();

        return redirect()->back()
            ->with('success', 'Enquiry deleted successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Show Enquiry with Followups
    |--------------------------------------------------------------------------
    */
    public function show(string $id)
    {
        $enquiry = Enquiry::with(['followUps.creator'])
            ->findOrFail($id);

        return view('admin.enquiries.show', compact('enquiry'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Followup
    |--------------------------------------------------------------------------
    */
    public function storeFollowup(Request $request, string $id)
    {
        $request->validate([
            'remarks'            => 'required|string',
            'next_followup_date' => 'required|date',
        ]);

        EnquiryFollowUp::create([
            'enquiry_id'        => $id,
            'created_by'        => Auth::id(),
            'followup_date'     => now(),
            'next_followup_date' => $request->next_followup_date,
            'remarks'           => $request->remarks,
            'status'            => $request->status,
        ]);

        Enquiry::where('id', $id)->update([
            'status' => 'next_followup',
            'next_followup_date' => $request->next_followup_date
        ]);

        return redirect()->back()
            ->with('success', 'Followup added successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Convert To Lead
    |--------------------------------------------------------------------------
    */
    public function convertToLead(string $id)
    {
        Enquiry::where('id', $id)->update([
            'status' => 'converted_to_lead'
        ]);

        return redirect()->back()
            ->with('success', 'Enquiry converted to Lead');
    }

    /*
    |--------------------------------------------------------------------------
    | Close Enquiry
    |--------------------------------------------------------------------------
    */
    public function close(string $id)
    {
        Enquiry::where('id', $id)->update([
            'status' => 'closed'
        ]);

        return redirect()->back()
            ->with('success', 'Enquiry closed successfully');
    }

    public function markToClose($id)
    {
        $enquiry = Enquiry::findOrFail($id);

        if ($enquiry->status == 'closed') {
            return back()->with('error', 'Already closed');
        }

        $enquiry->update([
            'status' => 'mark_to_close'
        ]);

        return back()->with('success', 'Enquiry marked to close successfully');
    }
}
