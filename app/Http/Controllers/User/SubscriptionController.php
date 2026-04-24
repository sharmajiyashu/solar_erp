<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ServicePackage;
use App\Services\Solar\SolarSubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class SubscriptionController extends Controller
{
    private Api $razorpayApi;

    public function __construct(
        private SolarSubscriptionService $subscriptionService
    ) {
        $this->razorpayApi = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    public function initiatePayment(Request $request)
    {
        $request->validate(['package_id' => 'required|exists:service_packages,id']);
        $package = ServicePackage::findOrFail($request->package_id);

        try {
            $order = $this->razorpayApi->order->create([
                'receipt' => 'rcpt_'.time(),
                'amount' => $package->price * 100,
                'currency' => 'INR',
            ]);

            return response()->json([
                'status' => true,
                'order_id' => $order['id'],
                'amount' => $package->price,
                'package_name' => $package->name,
                'key' => env('RAZORPAY_KEY'),
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay Order Error: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to initiate payment.'], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:service_packages,id',
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
            'start_date' => 'nullable|date|after_or_equal:today',
        ]);

        $package = ServicePackage::findOrFail($request->package_id);

        try {
            $this->razorpayApi->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Payment verification failed.'], 403);
        }

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfDay();

        try {
            $this->subscriptionService->createFromPurchase(
                Auth::user(),
                $package,
                $request->razorpay_payment_id,
                $request->razorpay_order_id,
                $request->razorpay_signature,
                $startDate
            );
        } catch (\Throwable $e) {
            Log::error('Subscription Creation Error: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to process subscription.'], 500);
        }

        return response()->json(['status' => true, 'message' => 'Subscription activated successfully!']);
    }
}
