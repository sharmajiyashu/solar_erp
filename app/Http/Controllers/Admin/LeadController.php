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


    public function __construct()
    {
        $this->middleware('permission:leads view')->only(['index', 'show']);
        $this->middleware('permission:leads create')->only(['create', 'store', 'edit', 'update', 'moveStage']);
        
        $this->middleware('permission:site_visits schedule')->only(['siteVisit', 'storeVisit']);
        $this->middleware('permission:site_visits edit')->only(['updateVisit']);
        
        $this->middleware('permission:quotations view')->only(['quotation']);
        $this->middleware('permission:document_management view')->only(['document']);
        $this->middleware('permission:backend_management view')->only(['backend']);
        $this->middleware('permission:procurement_management view')->only(['procurement']);
        $this->middleware('permission:installation_management view')->only(['installation']);
        $this->middleware('permission:verification_management view')->only(['verification']);
        $this->middleware('permission:project_completion view')->only(['completed']);
    }

    public function edit($id)
    {
        $lead = Lead::find($id);
        return view('admin.leads.create', compact('lead'));
    }

    public function create()
    {
        return view('admin.leads.create');
    }


    public function update(Request $request, $id)
    {
        if ($request->has('is_completion')) {
            $lead = Lead::findOrFail($id);

            DB::beginTransaction();

            try {
                // Update remarks if provided
                if ($request->has('remarks')) {
                    $lead->update(['remarks' => $request->remarks]);
                }

                // Handle handover and final status
                if ($request->has('handover_done')) {
                    $stages = $lead->project_stages;
                    if (is_string($stages)) {
                        $stages = json_decode($stages, true);
                    }

                    $stages['completed']['status'] = 'done';
                    $stages['completed']['completed_at'] = now();

                    $lead->update([
                        'status' => 'completed',
                        'project_stages' => $stages
                    ]);
                }

                // Photo upload
                if ($request->hasFile('completion_photos')) {
                    foreach ($request->file('completion_photos') as $file) {
                        $destinationPath = public_path('uploads/completions');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }

                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->move($destinationPath, $fileName);

                        // Save in documents table as completion photos
                        $lead->documents()->create([
                            'document_type' => 'Completion Photo',
                            'file_path' => 'uploads/completions/' . $fileName,
                            'status' => 'verified'
                        ]);
                    }
                }

                DB::commit();
                return back()->with('success', 'Project completion details updated successfully.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Something went wrong: ' . $e->getMessage());
            }
        }

        // Original logic for lead update (Site Visit assignment)
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'visit_date' => 'required',
            'remarks'     => 'required|string',
        ]);

        Lead::where('id', $id)->update([
            'remarks' => $request->remarks,
        ]);

        SiteVisit::create([
            'lead_id' => $id,
            'user_id' => $request->assigned_to,
            'visit_date' => $request->visit_date,
            'status' => 'pending',
            'notes' => null
        ]);

        if ($request->ajax()) {
            return route('admin.leads.show', $id);
        }

        return redirect()->route('admin.leads.show', $id)->with('success', 'Lead updated successfully');
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


            $year = now()->format('Y');
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
                'document' => [
                    'status' => 'pending',
                    'completed_at' => null
                ],
                'backend' => [
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


    public function siteVisit(Request $request)
    {
        return $this->stageView('site_visit', $request);
    }

    public function quotation(Request $request)
    {
        return $this->stageView('quotation', $request);
    }

    public function document(Request $request)
    {
        return $this->stageView('document', $request);
    }

    public function backend(Request $request)
    {
        return $this->stageView('backend', $request);
    }

    public function procurement(Request $request)
    {
        return $this->stageView('procurement', $request);
    }

    public function installation(Request $request)
    {
        return $this->stageView('installation', $request);
    }

    public function verification(Request $request)
    {
        return $this->stageView('verification', $request);
    }

    public function completed(Request $request)
    {
        return $this->stageView('completed', $request);
    }

    /*
    |--------------------------------------------------------------------------
    | Common Stage View Logic
    |--------------------------------------------------------------------------
    */


    public function index(Request $request)
    {
        $query_search = $request->input('search');

        $leads = Lead::with(['customer'])
            ->when(!Auth::user()->can('leads get-all'), function ($query) {
                $query->where(function($q) {
                    $q->where('created_by', Auth::id())
                      ->orWhere('assigned_to', Auth::id());
                });
            })
            ->search($request->search)
            ->latest()
            ->paginate(20);

        if ($request->ajax()) {
            return view('admin.leads.pagination', compact('leads'))->render();
        }

        return view('admin.leads.index', compact('leads'));
    }

    private function stageView($stage, Request $request)
    {
        $query_search = $request->input('search');

        $leads = Lead::with(['customer'])
            ->where('stage', $stage)
            ->when(true, function ($query) use ($stage) {
                if (Auth::user()->can('leads get-all')) {
                    return $query;
                }
                
                if ($stage === 'site_visit' && Auth::user()->can('site_visits get-all')) {
                    return $query;
                }
                
                if ($stage === 'quotation' && Auth::user()->can('quotations get-all')) {
                    return $query;
                }

                return $query->where(function($q) {
                    $q->where('created_by', Auth::id())
                      ->orWhere('assigned_to', Auth::id());
                });
            })
            ->search($request->search)
            ->latest()
            ->paginate(20);

        if ($request->ajax()) {
            return view('admin.leads.pagination', compact('leads', 'stage'))->render();
        }

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
        if (is_string($stages)) {
            $stages = json_decode($stages, true);
        }

        // Mark current stage as done
        $stages[$lead->stage]['status'] = 'done';
        $stages[$lead->stage]['completed_at'] = now();

        // Update lead stage
        $lead->update([
            'project_stages' => $stages,
            'stage' => $nextStage,
            'status' => 'pending'
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

    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();
        return back()->with('success', 'Lead Deleted Successfully');
    }

    public function ownLead($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->update([
            'assigned_to' => Auth::id()
        ]);
        return back()->with('success', 'Lead Owned Successfully');
    }
}
