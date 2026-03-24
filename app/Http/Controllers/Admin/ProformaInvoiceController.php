<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProformaInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:items_management view')->only(['view']);
        $this->middleware('permission:items_management create')->only(['generate']);
    }

    public function generate($id)
    {
        $lead = Lead::with(['customer', 'procurementItems.product'])->findOrFail($id);
        
        if ($lead->procurementItems->isEmpty()) {
            return back()->with('error', 'No procurement items found. Please add items first.');
        }

        $pdf = Pdf::loadView('admin.leads.proforma_invoice', compact('lead'));
        
        return $pdf->download("Proforma_Invoice_{$lead->lead_no}.pdf");
    }

    public function view($id)
    {
        $lead = Lead::with(['customer', 'procurementItems.product'])->findOrFail($id);
        
        if ($lead->procurementItems->isEmpty()) {
            return back()->with('error', 'No procurement items found. Please add items first.');
        }

        return view('admin.leads.proforma_invoice', compact('lead'));
    }
}
