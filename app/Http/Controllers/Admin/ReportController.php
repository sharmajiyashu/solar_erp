<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reports view')->only('index');
        $this->middleware('permission:reports export')->only('exportCsv');
    }

    public function index(Request $request)
    {
        $query = Enquiry::with(['creator', 'lead.installation', 'lead.verificationReport', 'lead.assignedUser']);

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('enquiry_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enquiries = $query->latest()->paginate(20);

        return view('admin.reports.index', compact('enquiries'));
    }

    public function exportCsv(Request $request)
    {
        $query = Enquiry::with(['creator', 'lead.installation', 'lead.verificationReport']);

        // Apply filters same as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('enquiry_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enquiries = $query->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=enquiry_lead_report_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Enquiry No', 'Customer Name', 'Mobile', 'Enquiry Status', 'Created By',
            'Lead No', 'Current Stage', 'Lead Status',
            'Discom Login', 'Bank Login', '1st Payment Received', 'Token Amount', 'Is Documents Done', 'Is Handover Done',
            'Site Visit Status', 'Quotation Status', 'Document Status', 'Backend Status', 
            'Procurement Status', 'Installation Status', 'Verification Status', 'Completed Status',
            'Installation Done', 'Net Metering Done',
            'Docs for 2nd Tranch', '2nd Tranch Received', 'Is Verified',
            'Created At'
        ];

        $callback = function() use($enquiries, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $workflowStages = [
                'site_visit', 'quotation', 'document', 'backend', 
                'procurement', 'installation', 'verification', 'completed'
            ];

            foreach ($enquiries as $enquiry) {
                $lead = $enquiry->lead;
                $row = [
                    $enquiry->enquiry_no,
                    $enquiry->customer_name,
                    $enquiry->mobile,
                    ucfirst(str_replace('_', ' ', $enquiry->status)),
                    $enquiry->creator ? $enquiry->creator->name : 'N/A',
                    $lead ? $lead->lead_no : 'N/A',
                    $lead ? ucfirst(str_replace('_', ' ', $lead->stage)) : 'N/A',
                    $lead ? ucfirst($lead->status) : 'N/A',
                ];

                // Detailed Lead Info
                if ($lead) {
                    $row[] = $lead->discom_pms_portal_login_done ? 'Yes' : 'No';
                    $row[] = $lead->bank_login_done ? 'Yes' : 'No';
                    $row[] = $lead->first_payment_received ? 'Yes' : 'No';
                    $row[] = $lead->token_amount ?? '0';
                    $row[] = $lead->is_document_done ? 'Yes' : 'No';
                    $row[] = $lead->handover_by ? 'Yes' : 'No';
                } else {
                    $row[] = 'N/A'; $row[] = 'N/A'; $row[] = 'N/A'; $row[] = 'N/A'; $row[] = 'N/A'; $row[] = 'N/A';
                }

                // Add workflow stage statuses
                if ($lead) {
                    $stages = $lead->project_stages;
                    if (is_string($stages)) {
                        $stages = json_decode($stages, true);
                    }
                    foreach ($workflowStages as $stage) {
                        $status = isset($stages[$stage]) ? $stages[$stage]['status'] : 'pending';
                        $row[] = ucfirst($status);
                    }

                    // Granular Installation
                    $installation = $lead->installation;
                    $row[] = ($installation && $installation->installation_done) ? 'Yes' : 'No';
                    $row[] = ($installation && $installation->net_metering_done) ? 'Yes' : 'No';

                    // Granular Verification
                    $vReport = $lead->verificationReport;
                    $row[] = ($vReport && $vReport->is_docs_proceed_for_2nd_tranch) ? 'Yes' : 'No';
                    $row[] = ($vReport && $vReport->second_tier_payment_received) ? 'Yes' : 'No';
                    $row[] = ($vReport && $vReport->is_verified) ? 'Yes' : 'No';
                } else {
                    for ($i = 0; $i < 8; $i++) $row[] = 'N/A'; // stages
                    $row[] = 'N/A'; $row[] = 'N/A'; // installation
                    $row[] = 'N/A'; $row[] = 'N/A'; $row[] = 'N/A'; // verification
                }

                $row[] = $enquiry->created_at->format('Y-m-d H:i');

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
