<?php

namespace App\Http\Controllers;

use App\Models\WebsiteEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebsiteEnquiryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:contact,quotation',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            WebsiteEnquiry::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'subject' => $request->subject,
                'message' => $request->message,
                'type' => $request->type,
                'status' => 'pending',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Your enquiry has been submitted successfully. Our team will contact you soon.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
