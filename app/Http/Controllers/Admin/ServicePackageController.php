<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServicePackage;

class ServicePackageController extends Controller
{
    public function index()
    {
        $packages = ServicePackage::latest()->paginate(10);
        return view('admin.service-packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'features' => 'nullable|array',
            'status' => 'nullable|boolean',
        ]);

        ServicePackage::create($request->all());

        return response()->json(['success' => 'Service Package created successfully.']);
    }

    public function edit(string $id)
    {
        $package = ServicePackage::findOrFail($id);
        return response()->json($package);
    }

    public function update(Request $request, string $id)
    {
        $package = ServicePackage::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'features' => 'nullable|array',
            'status' => 'nullable|boolean',
        ]);

        $package->update($request->all());

        return response()->json(['success' => 'Service Package updated successfully.']);
    }

    public function destroy(string $id)
    {
        ServicePackage::findOrFail($id)->delete();
        return response()->json(['success' => 'Service Package deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $package = ServicePackage::findOrFail($id);
        $package->status = $request->status;
        $package->save();

        return response()->json(['success' => 'Status updated successfully.']);
    }
}
