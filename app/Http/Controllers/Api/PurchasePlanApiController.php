<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solar\PurchasePlanRequest;
use App\Models\ServicePackage;
use App\Services\Solar\SolarSubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class PurchasePlanApiController extends Controller
{
    public function __construct(
        private SolarSubscriptionService $subscriptionService
    ) {
        $this->middleware('auth');
    }

    public function __invoke(PurchasePlanRequest $request): JsonResponse
    {
        $package = ServicePackage::findOrFail($request->package_id);
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => 'Payment verification failed.'], 403);
        }

        try {
            $subscription = $this->subscriptionService->createFromPurchase(
                Auth::user(),
                $package,
                $request->razorpay_payment_id,
                $request->razorpay_order_id,
                $request->razorpay_signature,
                Carbon::parse($request->start_date)->startOfDay()
            );
        } catch (\Throwable $e) {
            Log::error('Purchase plan API: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to activate subscription.'], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Subscription activated.',
            'subscription_id' => $subscription->id,
            'total_slots' => $subscription->total_slots,
        ]);
    }
}
