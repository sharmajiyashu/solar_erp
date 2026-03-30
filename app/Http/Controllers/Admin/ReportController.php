<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Lead;
use App\Models\User;
use App\Models\Attendance;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reports view')->only(['index', 'attendanceReport', 'stockReport', 'paymentAnalysisReport']);
        $this->middleware('permission:reports export')->only(['exportCsv', 'exportAttendanceExcel', 'exportStockCsv', 'exportPaymentAnalysisCsv']);
    }

    public function attendanceReport(Request $request)
    {
        $month = $request->month ?? Carbon::now('Asia/Kolkata')->month;
        $year  = $request->year ?? Carbon::now('Asia/Kolkata')->year;
        $userId = $request->user_id;

        $users = User::orderBy('name')->get();

        $query = Attendance::with('user')
            ->whereMonth('date', $month)
            ->whereYear('date', $year);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(31);

        return view('admin.reports.attendance', compact('attendances', 'users', 'month', 'year', 'userId'));
    }

    public function exportAttendanceExcel(Request $request)
    {
        $month = $request->month ?? Carbon::now('Asia/Kolkata')->month;
        $year  = $request->year ?? Carbon::now('Asia/Kolkata')->year;
        $userId = $request->user_id;

        if ($userId) {
            $users = User::where('id', $userId)->get();
        } else {
            $users = User::orderBy('name')->get();
        }

        return Excel::download(new AttendanceExport($users, $month, $year), "attendance_report_" . date('Y-m-d') . ".xlsx");
    }

    public function index(Request $request)
    {
        $query = Enquiry::with(['creator', 'lead.installation', 'lead.verificationReport', 'lead.visits.user']);

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
        $query = Enquiry::with(['creator', 'lead.installation', 'lead.verificationReport', 'lead.visits.user']);

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
            'Enquiry No', 'Customer Name', 'Mobile', 'Capacity (KW)', 'Enquiry Status', 'Created By',
            'Lead No', 'Assigned To', 'Current Stage', 'Lead Status',
            'Discom Login', 'Bank Login', '1st Payment Received', 'Is Documents Done', 'Is Handover Done',
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
                    $enquiry->project_size ? $enquiry->project_size . ' KW' : 'N/A',
                    $lead ? $lead->lead_no : 'N/A',
                    ($lead && $lead->visits->count() > 0) ? $lead->visits->last()->user->name : 'N/A',
                    $lead ? ucfirst(str_replace('_', ' ', $lead->stage)) : 'N/A',
                    $lead ? ucfirst($lead->status) : 'N/A',
                ];

                // Detailed Lead Info
                if ($lead) {
                    $row[] = $lead->discom_pms_portal_login_done ? 'Yes' : 'No';
                    $row[] = $lead->bank_login_done ? 'Yes' : 'No';
                    $row[] = $lead->first_payment_received ? 'Yes' : 'No';
                    $row[] = $lead->is_document_done ? 'Yes' : 'No';
                    $row[] = $lead->handover_by ? 'Yes' : 'No';
                } else {
                    $row[] = 'N/A'; // Discom
                    $row[] = 'N/A'; // Bank
                    $row[] = 'N/A'; // 1st payment rec
                    $row[] = 'N/A'; // Is Docs done
                    $row[] = 'N/A'; // Handover
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

                // Financials (At the end)
                

                $row[] = $enquiry->created_at->format('Y-m-d H:i');

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function stockReport(Request $request)
    {
        $query = \App\Models\Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subtype', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('subtype')->paginate(20);

        return view('admin.reports.stock', compact('products'));
    }

    public function exportStockCsv(Request $request)
    {
        $query = \App\Models\Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subtype', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('subtype')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=current_stock_report_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Category', 'Product Subtype', 'Company', 'Landing (wo GST)', 'GST (%)', 'Tax Amount', 'Final Landing', 'Current Stock', 'Total Value', 'Last Updated'];

        $callback = function() use($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $product) {
                $totalValue = ($product->stock ?? 0) * ($product->final_landing_with_gst ?? 0);
                $row = [
                    $product->category ? $product->category->name : 'N/A',
                    $product->subtype,
                    $product->company,
                    $product->total_landing_wo_gst ?? 0,
                    $product->gst_percentage ?? 0,
                    $product->tax_amount ?? 0,
                    $product->final_landing_with_gst ?? 0,
                    $product->stock ?? 0,
                    $totalValue,
                    $product->updated_at->format('Y-m-d H:i'),
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function paymentAnalysisReport(Request $request)
    {
        $query = Enquiry::with(['creator', 'lead.verificationReport', 'lead.procurementItems']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('enquiry_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $enquiries = $query->latest()->paginate(20);

        return view('admin.reports.payment_analysis', compact('enquiries'));
    }

    public function exportPaymentAnalysisCsv(Request $request)
    {
        $query = Enquiry::with(['creator', 'lead.verificationReport', 'lead.procurementItems']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('enquiry_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $enquiries = $query->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=payment_analysis_report_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Date', 'Customer Name', 'Mobile No.', 'KW', 'CURRENT STATUS', 'Source By', 'Loan/Cash',
            'Quotation', 'Token Money', '1st Tranche', '2nd Tranche', 'Total Amount Credit',
            'Proforma Invoice Amt', 'Tax Invoice Amt', 'Payout', 'DIVIDED BIT', 'NET SAVING',
            'Admin Collection Pending Amt', '1st Tranche Date'
        ];

        $callback = function() use($enquiries, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($enquiries as $enquiry) {
                $lead = $enquiry->lead;
                $vReport = $lead ? $lead->verificationReport : null;
                
                $token = $lead->token_amount ?? 0;
                $tranch1 = $lead->first_tranche_amount ?? 0;
                $tranch2 = $vReport->second_tranche_amount ?? 0;
                $totalCredit = $token + $tranch1 + $tranch2;
                
                $quotation = $vReport->quotation_price ?? 0;
                $proforma = $lead ? $lead->procurementItems->sum('total') : 0;
                $taxInvoice = $vReport->tax_invoice_amount ?? 0;
                $payout = $vReport->payout_amount ?? 0;
                
                $dividedBit = ($taxInvoice - $quotation) * 0.15;
                $netSaving = $quotation - ($proforma + $payout + $dividedBit);
                $pending = $quotation - $totalCredit;

                $row = [
                    $enquiry->created_at->format('d/m/Y'),
                    $enquiry->customer_name,
                    $enquiry->mobile,
                    $enquiry->project_size,
                    $lead ? ucfirst(str_replace('_', ' ', $lead->stage)) : ($enquiry->status == 'converted_to_lead' ? 'Lead' : ucfirst($enquiry->status)),
                    $enquiry->creator->name ?? 'N/A',
                    $lead->lead_type ?? 'N/A',
                    $quotation,
                    $token,
                    $tranch1,
                    $tranch2,
                    $totalCredit,
                    $proforma,
                    $taxInvoice,
                    $payout,
                    round($dividedBit, 2),
                    round($netSaving, 2),
                    $pending,
                    $vReport && $vReport->first_tranche_date ? $vReport->first_tranche_date->format('d/m/Y') : 'N/A'
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
