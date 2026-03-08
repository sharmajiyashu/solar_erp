<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function store(Request $request, $leadId)
    {
        $request->validate([
            'quotation_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'items' => 'required|array',
            'items.*.item_name' => 'required',
            'items.*.quantity' => 'required|numeric'
        ]);

        DB::beginTransaction();

        try {

            $quotationNo = 'QT-' . time();

            $quotation = Quotation::create([
                'lead_id' => $leadId,
                'quotation_no' => $quotationNo,
                'quotation_date' => $request->quotation_date,
                'total_amount' => $request->total_amount,
                'status' => 'draft'
            ]);

            foreach ($request->items as $item) {

                $quotation->items()->create([
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => 0,
                    'total' => 0
                ]);
            }

            DB::commit();

            return back()->with('success', 'Quotation Created Successfully');
        } catch (\Exception $e) {

            DB::rollback();

            return  $e->getMessage();
        }
    }

    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->items()->delete();
        $quotation->delete();

        return back()->with('success', 'Quotation Deleted Successfully');
    }
}
