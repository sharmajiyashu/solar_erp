<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'status' => true,
        ]);

        return response()->json(['success' => 'Category created successfully.']);
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => 'Category updated successfully.']);
    }

    public function destroy(string $id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(['success' => 'Category deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->status = $request->status;
        $category->save();

        return response()->json(['success' => 'Status updated successfully.']);
    }
}
