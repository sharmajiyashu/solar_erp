<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ServicePackage;

class FrontendController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function service()
    {
        $packages = ServicePackage::where('status', true)->get();
        return view('frontend.service', compact('packages'));
    }

    public function project()
    {
        return view('frontend.project');
    }

    public function feature()
    {
        return view('frontend.feature');
    }

    public function team()
    {
        return view('frontend.team');
    }

    public function testimonial()
    {
        return view('frontend.testimonial');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function quote()
    {
        return view('frontend.quote');
    }
}
