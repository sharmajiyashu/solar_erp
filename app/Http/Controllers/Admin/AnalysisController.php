<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    /**
     * Display the analysis page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with('category')->where('status', 1)->get();
        $categories = Category::where('status', 1)->get();
        $companies = Product::where('status', 1)->whereNotNull('company')->distinct()->pluck('company');
        
        return view('admin.products.analysis', compact('products', 'categories', 'companies'));
    }
}
