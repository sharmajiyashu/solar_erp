<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DispatchDetail;
use App\Models\ProcurementItem;
use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DispatchDetailController extends Controller
{
    public function __construct()
    {
        // User wants 'procurement_management view' to manage logistics
        $this->middleware('permission:procurement_management view')->only(['storeOrUpdate']); 
        
        $this->middleware('permission:items_management create')->only(['addProcurementItem']);
        $this->middleware('permission:items_management delete')->only(['removeProcurementItem']);
        $this->middleware('permission:items_management view')->only(['index', 'show']); // If they existed
    }

    public function storeOrUpdate(Request $request, $leadId)
    {
        $request->validate([
            'transporter_name' => 'nullable',
            'vehicle_number' => 'nullable',
            'driver_contact' => 'nullable',
            'dispatch_date' => 'nullable|date',
            'challan_book' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $data = [
            'transporter_name' => $request->transporter_name,
            'vehicle_number' => $request->vehicle_number,
            'driver_contact' => $request->driver_contact,
            'dispatch_date' => $request->dispatch_date,
        ];
        

        // FILE UPLOAD
        if ($request->hasFile('challan_book')) {

            $file = $request->file('challan_book');

            $destinationPath = public_path('uploads/challan_books');

            // folder create if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move($destinationPath, $fileName);

            $data['challan_book'] = 'uploads/challan_books/' . $fileName;
        }

        DispatchDetail::updateOrCreate(
            [
                'lead_id' => $leadId
            ],
            $data
        );

        return back()->with('success', 'Dispatch Details Saved');
    }

    public function addProcurementItem(Request $request, $leadId)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'gst_percentage' => 'required|numeric',
            'tax_amount' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            $lead = \App\Models\Lead::findOrFail($leadId);

            // Prevent duplicate submission (same lead, product, quantity within 5 seconds)
            $existing = ProcurementItem::where('lead_id', $leadId)
                ->where('product_id', $request->product_id)
                ->where('quantity', $request->quantity)
                ->where('created_at', '>=', now()->subSeconds(5))
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => 'Item added and stock updated.',
                    'item' => $existing->load('product'),
                    'duplicate' => true
                ]);
            }

            // Deduct stock
            $product->decrement('stock', $request->quantity);

            // Log stock history
            StockHistory::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'quantity' => $request->quantity,
                'type' => 'less',
                'reason' => "Procurement for Lead #{$lead->lead_no}"
            ]);

            // Create procurement item
            $item = ProcurementItem::create([
                'lead_id' => $leadId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'gst_percentage' => $request->gst_percentage,
                'tax_amount' => $request->tax_amount,
                'total' => $request->total
            ]);

            DB::commit();
            return response()->json([
                'success' => 'Item added and stock updated.',
                'item' => $item->load('product')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeProcurementItem($id)
    {
        DB::beginTransaction();
        try {
            $item = ProcurementItem::with('lead')->findOrFail($id);
            $product = Product::findOrFail($item->product_id);

            // Add back stock
            $product->increment('stock', $item->quantity);

            // Log stock history
            StockHistory::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'quantity' => $item->quantity,
                'type' => 'add',
                'reason' => "Removal from Procurement Lead #{$item->lead->lead_no}"
            ]);

            $item->delete();

            DB::commit();
            return response()->json(['success' => 'Item removed and stock restored.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
