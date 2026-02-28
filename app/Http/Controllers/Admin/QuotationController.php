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
            'quotation_date' => 'required',
            'items' => 'required|array'
        ]);

        DB::beginTransaction();

        try {

            $quotationNo = 'QT-' . time();
            $subtotal = 0;

            $quotation = Quotation::create([
                'lead_id' => $leadId,
                'quotation_no' => $quotationNo,
                'quotation_date' => $request->quotation_date,
                'status' => 'draft'
            ]);

            foreach ($request->items as $item) {

                $total = $item['quantity'] * $item['price'];
                $subtotal += $total;

                $quotation->items()->create([
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $total
                ]);
            }

            $gst = ($subtotal * 18) / 100;
            $grandTotal = $subtotal + $gst;

            $quotation->update([
                'subtotal' => $subtotal,
                'gst_amount' => $gst,
                'total_amount' => $grandTotal
            ]);

            DB::commit();

            return back()->with('success', 'Quotation Created');
        } catch (\Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
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
