<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\EnquiryFollowUp;
use App\Models\Lead;
use App\Models\SiteVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'address'           => 'nullable|string',
            'city'              => 'nullable|string',
            'source'            => 'nullable|string',
            'remarks'           => 'nullable|string',
            'next_followup_date' => 'nullable|date',
            'solar_type'         => 'required',
            'price_quote'         => 'required',
            'status' => 'required',
            'project_size' => 'required',
        ]);

        $validated['enquiry_no'] = 'ENQ-' . now()->timestamp;
        $validated['created_by'] = Auth::id();

        $enquiry = Enquiry::create($validated);
        if ($enquiry->status == 'converted_to_lead') {
            $leadId = $this->leadGenerate($enquiry->id);
            return redirect()->route('admin.leads.edit', $leadId)
                ->with('success', 'Enquiry converted to Lead');
        }


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

        if ($request->status == 'converted_to_lead') {
            $leadId = $this->leadGenerate($id);
            return redirect()->route('admin.leads.edit', $leadId)
                ->with('success', 'Enquiry converted to Lead');
        }

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


    public function convertToLead($id)
    {
        DB::beginTransaction();

        try {

            $enquiry = Enquiry::findOrFail($id);

            $enquiry->update([
                'status' => 'converted_to_lead'
            ]);

            $leadId = $this->leadGenerate($id);

            DB::commit();

            return redirect()->route('admin.leads.edit', $leadId)
                ->with('success', 'Enquiry converted to Lead');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }




    public function leadGenerate($enquiry_id)
    {
        $leadCheck = Lead::where('enquiry_id', $enquiry_id)->first();

        if ($leadCheck) {
            return $leadCheck->id;
        }

        $enquiry = Enquiry::findOrFail($enquiry_id);

        $customerCode = 'CUST-' . now()->format('Ymd') . '-' . rand(1000, 9999);

        $customer = Customer::updateOrCreate(
            [
                'mobile' => $enquiry->mobile
            ],
            [
                'customer_code' => $customerCode,
                'name' => $enquiry->customer_name,
                'address' => $enquiry->address,
                'city' => $enquiry->city,
                'created_by' => Auth::id()
            ]
        );

        $year = now()->format('Y');
        $month = now()->format('m');

        $prefix = "APS{$year}{$month}";

        $count = Lead::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $series = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        $leadNo = $prefix . $series;

        $projectStages = [
            'site_visit' => ['status' => 'pending', 'completed_at' => null],
            'quotation' => ['status' => 'pending', 'completed_at' => null],
            'bank' => ['status' => 'pending', 'completed_at' => null],
            'discom' => ['status' => 'pending', 'completed_at' => null],
            'dispatch' => ['status' => 'pending', 'completed_at' => null],
            'installation' => ['status' => 'pending', 'completed_at' => null],
            'verification' => ['status' => 'pending', 'completed_at' => null],
            'completed' => ['status' => 'pending', 'completed_at' => null],
        ];

        $lead = Lead::create([
            'enquiry_id' => $enquiry_id,
            'lead_no' => $leadNo,
            'customer_id' => $customer->id, // valid id
            'assigned_to' => Auth::id(),
            'stage' => 'site_visit',
            'status' => 'pending',
            'project_stages' => json_encode($projectStages),
            'remarks' => '',
            'created_by' => Auth::id(),
        ]);

        return $lead->id;
    }



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
