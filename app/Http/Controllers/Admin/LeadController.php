<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Quotation;
use App\Models\SiteVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Stage Based Listing
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.leads.create');
    }

    public function store(Request $request)
    {
        $request->validate([

            // Customer Details
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|string',

            // Lead Details
            'assigned_to' => 'required|exists:users,id',
            'visit_date' => 'required',
            'remarks'     => 'required|string',

        ]);

        DB::beginTransaction();

        try {

            /*
        |--------------------------------------------------------------------------
        | CUSTOMER CREATE OR UPDATE (CRM BEST PRACTICE ⭐)
        |--------------------------------------------------------------------------
        */
            $customerCode = 'CUST-' . now()->format('Ymd') . '-' . rand(1000, 9999);

            $customer = Customer::updateOrCreate(
                [
                    'mobile' => $request->customer_phone
                ],
                [
                    'customer_code' => $customerCode,
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'address' => $request->customer_address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'pincode' => $request->pincode,
                    'created_by' => Auth::id()
                ]
            );


            $year = now()->format('y');
            $month = now()->format('m');

            $prefix = "APS{$year}{$month}";

            // current month leads count
            $count = Lead::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $series = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

            $leadNo = $prefix . $series;


            $projectStages = [
                'site_visit' => [
                    'status' => 'pending',
                    'completed_at' => null
                ],
                'quotation' => [
                    'status' => 'pending',
                    'completed_at' => null
                ],
                'bank' => [
                    'status' => 'pending',
                    'completed_at' => null
                ],
                'discom' => [
                    'status' => 'pending',
                    'completed_at' => null
                ],
                'dispatch' => [
                    'status' => 'pending',
                    'completed_at' => null
                ],
                'installation' => [
                    'status' => 'pending',
                    'completed_at' => null
                ],
                'verification' => [
                    'status' => 'pending',
                    'completed_at' => null
                ],
                'completed' => [
                    'status' => 'pending',
                    'completed_at' => null
                ],
            ];

            $lead =   Lead::create([
                'lead_no' => $leadNo,
                'customer_id' => $customer->id,
                'assigned_to' => $request->assigned_to,
                'stage' => 'site_visit',
                'status' => 'pending',
                'project_stages' => $projectStages,
                'remarks' => $request->remarks,
                'created_by' => Auth::id(),
            ]);

            SiteVisit::create([
                'lead_id' => $lead->id,
                'user_id' => $request->assigned_to,
                'visit_date' => $request->visit_date,
                'status' => 'pending',
                'notes' => $request->remarks
            ]);


            DB::commit();
            session()->flash('success', 'Lead Create Successfully');

            return route('admin.leads.show', $lead->id);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function pending()
    {
        return $this->stageView('pending_lead');
    }

    public function siteVisit()
    {
        return $this->stageView('site_visit');
    }

    public function quotation()
    {
        return $this->stageView('quotation');
    }

    public function bank()
    {
        return $this->stageView('bank');
    }

    public function discom()
    {
        return $this->stageView('discom');
    }

    public function dispatch()
    {
        return $this->stageView('dispatch');
    }

    public function installation()
    {
        return $this->stageView('installation');
    }

    public function verification()
    {
        return $this->stageView('verification');
    }

    public function completed()
    {
        return $this->stageView('completed');
    }

    /*
    |--------------------------------------------------------------------------
    | Common Stage View Logic
    |--------------------------------------------------------------------------
    */

    private function stageView($stage)
    {
        $leads = Lead::with(['customer', 'assignedUser'])
            ->where('stage', $stage)
            ->latest()
            ->paginate(20);

        return view('admin.leads.index', compact('leads', 'stage'));
    }

    /*
    |--------------------------------------------------------------------------
    | Show Single Lead
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $lead = Lead::with(['customer', 'assignedUser', 'creator'])
            ->findOrFail($id);

        $quotation = Quotation::with('items', 'lead')->where('lead_id', $id)
            ->first();

        $activeStage = $lead->stage;
        return view('admin.leads.show', compact('lead', 'quotation', 'activeStage'));
    }

    /*
    |--------------------------------------------------------------------------
    | Move Lead To Next Stage
    |--------------------------------------------------------------------------
    */

    public function moveStage($id, $nextStage)
    {
        $lead = Lead::findOrFail($id);
        $stages = $lead->project_stages;

        // Mark current stage as done
        $stages[$lead->stage]['status'] = 'done';
        $stages[$lead->stage]['completed_at'] = now();

        // Update lead stage
        $lead->update([
            'project_stages' => $stages,
            'stage' => $nextStage,
            'status' => 'in_progress'
        ]);

        return back()->with('success', 'Lead moved to next stage successfully.');
    }


    public function storeVisit(Request $request, $lead_id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'visit_date' => 'required|date',
            'status' => 'required',
            'notes' => 'nullable|string',
        ]);

        SiteVisit::create([
            'lead_id' => $lead_id,
            'user_id' => $request->user_id,
            'visit_date' => $request->visit_date,
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Visit created successfully');
    }

    public function updateVisit(Request $request, $id)
    {
        $request->validate([
            'visit_date' => 'required|date',
            'status' => 'required',
            'notes' => 'nullable|string',
            'user_id' => 'required|exists:users,id'
        ]);

        SiteVisit::where('id', $id)->update([
            'visit_date' => $request->visit_date,
            'status' => $request->status,
            'notes' => $request->notes,
            'user_id' => $request->user_id,
        ]);

        return back()->with('success', 'Visit Updated Successfully');
    }
}
