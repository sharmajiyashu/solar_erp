<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockHistory;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subtype', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(10);
        $categories = Category::where('status', true)->get();

        if ($request->ajax()) {
            return view('admin.products._table', compact('products'))->render();
        }

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subtype' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'total_landing_wo_gst' => 'required|numeric',
            'gst_percentage' => 'required|numeric',
            'tax_amount' => 'required|numeric',
            'final_landing_with_gst' => 'required|numeric',
            'three_kw_dcr_qnt' => 'nullable|string|max:255',
        ]);

        Product::create($request->all());

        return response()->json(['success' => 'Product created successfully.']);
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subtype' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'total_landing_wo_gst' => 'required|numeric',
            'gst_percentage' => 'required|numeric',
            'tax_amount' => 'required|numeric',
            'final_landing_with_gst' => 'required|numeric',
            'three_kw_dcr_qnt' => 'nullable|string|max:255',
        ]);

        $product->update($request->all());

        return response()->json(['success' => 'Product updated successfully.']);
    }

    public function destroy(string $id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['success' => 'Product deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $status = (boolean)$request->status;
        $product->status = $status;
        $product->save();

        return response()->json([
            'success' => 'Status changed to ' . ($status ? 'Active' : 'Inactive'),
            'status' => $status
        ]);
    }

    public function updateStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:add,less',
            'reason' => 'required|string|max:255',
        ]);

        $quantity = $request->quantity;

        // Prevent duplicate submission (same user, product, type, quantity within 5 seconds)
        $existing = StockHistory::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->where('type', $request->type)
            ->where('quantity', $quantity)
            ->where('created_at', '>=', now()->subSeconds(5))
            ->first();

        if ($existing) {
            return response()->json(['success' => 'Stock updated successfully (Duplicate request ignored).', 'new_stock' => $product->stock]);
        }

        return \DB::transaction(function () use ($product, $request, $quantity) {
            if ($request->type == 'less') {
                if ($product->stock < $quantity) {
                    return response()->json(['errors' => ['quantity' => ['Insufficient stock. Current stock: ' . $product->stock]]], 422);
                }
                $product->decrement('stock', $quantity);
            } else {
                $product->increment('stock', $quantity);
            }

            StockHistory::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'quantity' => $quantity,
                'type' => $request->type,
                'reason' => $request->reason,
            ]);

            $product->refresh();
            return response()->json(['success' => 'Stock updated successfully.', 'new_stock' => $product->stock]);
        });
    }

    public function stockHistory($id)
    {
        $product = Product::with('stockHistories.user')->findOrFail($id);
        $history = $product->stockHistories()->latest()->get();
        
        return response()->json([
            'product' => $product,
            'history' => $history
        ]);
    }
}
