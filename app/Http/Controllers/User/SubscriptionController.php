<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ServicePackage;
use App\Models\UserSubscription;
use App\Models\ServiceSlot;
use Razorpay\Api\Api;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    private $razorpayApi;

    public function __construct()
    {
        $this->razorpayApi = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    public function initiatePayment(Request $request)
    {
        $request->validate(['package_id' => 'required|exists:service_packages,id']);
        $package = ServicePackage::findOrFail($request->package_id);

        try {
            $order = $this->razorpayApi->order->create([
                'receipt' => 'rcpt_' . time(),
                'amount' => $package->price * 100, // in paise
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
            Log::error("Razorpay Order Error: " . $e->getMessage());
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
        ]);

        $package = ServicePackage::findOrFail($request->package_id);

        // Verify Signature
        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];
            $this->razorpayApi->utility->verifyPaymentSignature($attributes);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Payment verification failed.'], 403);
        }

        DB::beginTransaction();
        try {
            // Calculate end date based on duration_type
            $startDate = now();
            $monthsMap = ['monthly' => 1, '3_months' => 3, '6_months' => 6, '9_months' => 9, '12_months' => 12];
            $monthsToAdd = $monthsMap[$package->duration_type] ?? 1;
            $endDate = (clone $startDate)->addMonths($monthsToAdd);

            $subscription = UserSubscription::create([
                'user_id' => Auth::id(),
                'package_id' => $package->id,
                'amount' => $package->price,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_signature' => $request->razorpay_signature,
                'status' => 'active',
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            // Generate Slots
            $this->generateServiceSlots($subscription, $package);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Subscription activated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Subscription Creation Error: " . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to process subscription.'], 500);
        }
    }

    private function generateServiceSlots($subscription, $package)
    {
        $intervalMap = ['7_days' => 7, '15_days' => 15, '30_days' => 30];
        $daysInterval = $intervalMap[$package->frequency] ?? 30;
        
        $currentDate = Carbon::parse($subscription->start_date)->addDays($daysInterval);
        $endDate = Carbon::parse($subscription->end_date);

        while ($currentDate <= $endDate) {
            ServiceSlot::create([
                'subscription_id' => $subscription->id,
                'service_date' => $currentDate,
                'status' => 'pending',
            ]);
            $currentDate->addDays($daysInterval);
        }
    }
}
