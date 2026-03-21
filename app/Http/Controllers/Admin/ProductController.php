<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Category::where('status', true)->get();
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
        $product->status = $request->status;
        $product->save();

        return response()->json(['success' => 'Status updated successfully.']);
    }
}
