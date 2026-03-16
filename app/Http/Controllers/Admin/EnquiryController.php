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
    public function __construct()
    {
        $this->middleware('permission:enquiries view')->only(['index', 'show']);
        $this->middleware('permission:enquiries create')->only(['create', 'store', 'edit', 'update']);
        $this->middleware('permission:enquiries delete')->only(['destroy']);
        $this->middleware('permission:enquiries mark_to_close')->only(['markToClose']);
        $this->middleware('permission:enquiries close')->only(['close']);
    }

    
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

            ->when($change_status === null, function ($query) {
                $query->where('status', '!=', 'converted_to_lead');
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
            'alternate_mobile' => 'nullable|string',
            'email' => 'nullable|email',
            'pincode' => 'nullable|string',
            'assigned_to' => 'nullable|required_if:status,converted_to_lead|exists:users,id',
            'visit_date' => 'nullable|required_if:status,converted_to_lead|date',
        ]);

        $validated['enquiry_no'] = 'ENQ-' . now()->timestamp;
        $validated['created_by'] = Auth::id();

        $enquiry = Enquiry::create($validated);

        if ($request->ajax()) {
            if ($enquiry->status == 'converted_to_lead') {
                $leadId = $this->leadGenerate($enquiry->id, $request->assigned_to, $request->visit_date);
                session()->flash('success', 'Enquiry converted to Lead');
                return response()->json([
                    'success' => true,
                    'redirect' => route('admin.leads.show', $leadId)
                ]);
            }

            session()->flash('success', 'Enquiry created successfully');
            return response()->json([
                'success' => true,
                'redirect' => route('admin.enquiries.index')
            ]);
        }

        if ($enquiry->status == 'converted_to_lead') {
            $leadId = $this->leadGenerate($enquiry->id, $request->assigned_to, $request->visit_date);
            return redirect()->route('admin.leads.show', $leadId)
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
            'pincode'       => 'nullable|string',
            'alternate_mobile' => 'nullable|string',
            'status'        => 'required',
            'assigned_to' => 'nullable|required_if:status,converted_to_lead|exists:users,id',
            'visit_date' => 'nullable|required_if:status,converted_to_lead|date',
        ]);

        $enquiry = Enquiry::findOrFail($id);
        $enquiry->update($validated);

        if ($request->ajax()) {
            if ($request->status == 'converted_to_lead') {
                $leadId = $this->leadGenerate($id, $request->assigned_to, $request->visit_date);
                session()->flash('success', 'Enquiry converted to Lead');
                return response()->json([
                    'success' => true,
                    'redirect' => route('admin.leads.show', $leadId)
                ]);
            }

            session()->flash('success', 'Enquiry updated successfully');
            return response()->json([
                'success' => true,
                'redirect' => route('admin.enquiries.index')
            ]);
        }

        if ($request->status == 'converted_to_lead') {
            $leadId = $this->leadGenerate($id, $request->assigned_to, $request->visit_date);
            return redirect()->route('admin.leads.show', $leadId)
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
            'next_followup_date' => 'nullable|date|required_if:status,rescheduled,pending',
            'status'             => 'required|in:pending,completed,rescheduled',
        ]);

        EnquiryFollowUp::create([
            'enquiry_id'        => $id,
            'created_by'        => Auth::id(),
            'followup_date'     => now(),
            'next_followup_date' => $request->next_followup_date,
            'remarks'           => $request->remarks,
            'status'            => $request->status,
        ]);

        $updateData = [];
        if ($request->status == 'rescheduled' || $request->status == 'pending') {
            $updateData['status'] = 'next_followup';
            $updateData['next_followup_date'] = $request->next_followup_date;
        }

        if (!empty($updateData)) {
            Enquiry::where('id', $id)->update($updateData);
        }

        return redirect()->back()
            ->with('success', 'Followup added successfully');
    }


    public function editFollowup($followupId)
    {
        $followup = EnquiryFollowUp::findOrFail($followupId);
        return response()->json($followup);
    }

    public function updateFollowup(Request $request, $followupId)
    {
        $request->validate([
            'remarks'            => 'required|string',
            'next_followup_date' => 'nullable|date|required_if:status,rescheduled,pending',
            'status'             => 'required|in:pending,completed,rescheduled',
        ]);

        $followup = EnquiryFollowUp::findOrFail($followupId);
        $enquiryId = $followup->enquiry_id;

        $followup->update([
            'next_followup_date' => $request->next_followup_date,
            'remarks'           => $request->remarks,
            'status'            => $request->status,
        ]);

        $updateData = [];
        if ($request->status == 'rescheduled' || $request->status == 'pending') {
            $updateData['status'] = 'next_followup';
            $updateData['next_followup_date'] = $request->next_followup_date;
        } elseif ($request->status == 'completed') {
            // If they just mark it completed, we might want to keep the status but clear the date?
            // Actually, if it's completed, the enquiry is effectively "in progress" or "pending" again 
            // until a NEW followup is added.
            $updateData['status'] = 'pending'; 
            $updateData['next_followup_date'] = null;
        }

        if (!empty($updateData)) {
            Enquiry::where('id', $enquiryId)->update($updateData);
        }

        return redirect()->back()
            ->with('success', 'Followup updated successfully');
    }


    public function convertToLead(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'visit_date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {

            $enquiry = Enquiry::findOrFail($id);

            $enquiry->update([
                'status' => 'converted_to_lead'
            ]);

            $leadId = $this->leadGenerate($id, $request->assigned_to, $request->visit_date);

            DB::commit();

            return redirect()->route('admin.leads.show', $leadId)
                ->with('success', 'Enquiry created successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }




    public function leadGenerate($enquiry_id, $assigned_to = null, $visit_date = null)
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
                'state' => $enquiry->state,
                'pincode' => $enquiry->pincode,
                'created_by' => Auth::id()
            ]
        );

        $year = now()->format('Y');
        $month = now()->format('m');

        $prefix = "APS{$year}{$month}";

        $lastLead = Lead::where('lead_no', 'like', $prefix . '%')
            ->orderBy('lead_no', 'desc')
            ->first();

        if ($lastLead) {
            $lastNumber = intval(substr($lastLead->lead_no, strlen($prefix)));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $series = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $leadNo = $prefix . $series;

        $projectStages = [
            'site_visit' => ['status' => 'pending', 'completed_at' => null],
            'quotation' => ['status' => 'pending', 'completed_at' => null],
            'document' => ['status' => 'pending', 'completed_at' => null],
            'backend' => ['status' => 'pending', 'completed_at' => null],
            'procurement' => ['status' => 'pending', 'completed_at' => null],
            'installation' => ['status' => 'pending', 'completed_at' => null],
            'verification' => ['status' => 'pending', 'completed_at' => null],
            'completed' => ['status' => 'pending', 'completed_at' => null],
        ];

        $lead = Lead::create([
            'enquiry_id' => $enquiry_id,
            'lead_no' => $leadNo,
            'customer_id' => $customer->id, // valid id
            'assigned_to' => $assigned_to ?? Auth::id(),
            'stage' => 'site_visit',
            'status' => 'pending',
            'project_stages' => $projectStages,
            'remarks' => $enquiry->remarks ?? '',
            'created_by' => Auth::id(),
        ]);

        SiteVisit::create([
            'lead_id' => $lead->id,
            'user_id' => $assigned_to ?? Auth::id(),
            'visit_date' => $visit_date ?? now(),
            'status' => 'pending',
            'notes' => $enquiry->remarks ?? ''
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
