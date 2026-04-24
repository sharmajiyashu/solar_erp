<?php

namespace App\Services\Solar;

use App\Models\ServicePackage;
use App\Models\ServiceSlot;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SolarSubscriptionService
{
    public function __construct(
        private VerificationCodeGenerator $codes
    ) {}

    /**
     * Create subscription + slots after successful payment.
     *
     * @return UserSubscription
     */
    public function createFromPurchase(User $user, ServicePackage $package, string $razorpayPaymentId, string $razorpayOrderId, string $razorpaySignature, Carbon $startDate): UserSubscription
    {
        return DB::transaction(function () use ($user, $package, $razorpayPaymentId, $razorpayOrderId, $razorpaySignature, $startDate) {
            $monthsMap = ['monthly' => 1, '3_months' => 3, '6_months' => 6, '9_months' => 9, '12_months' => 12];
            $monthsToAdd = $monthsMap[$package->duration_type] ?? 1;
            $endDate = (clone $startDate)->copy()->addMonths($monthsToAdd)->endOfDay();

            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'amount' => $package->price,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_signature' => $razorpaySignature,
                'status' => 'active',
                'start_date' => $startDate->copy()->startOfDay(),
                'end_date' => $endDate,
                'duration_months' => $monthsToAdd,
                'total_slots' => 0,
            ]);

            $total = $this->generateSlotsForSubscription($subscription, $package);
            $subscription->update(['total_slots' => $total]);

            return $subscription->fresh(['slots']);
        });
    }

    public function generateSlotsForSubscription(UserSubscription $subscription, ServicePackage $package): int
    {
        $intervalMap = ['7_days' => 7, '15_days' => 15, '30_days' => 30];
        $daysInterval = (int) ($intervalMap[$package->frequency] ?? 30);

        $current = Carbon::parse($subscription->start_date)->startOfDay();
        $end = Carbon::parse($subscription->end_date)->startOfDay();

        $count = 0;
        while ($current <= $end) {
            ServiceSlot::create([
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'service_date' => $current->copy(),
                'status' => ServiceSlot::STATUS_PENDING,
                'verification_code' => $this->codes->unique(),
            ]);
            $count++;
            $current->addDays($daysInterval);
        }

        return $count;
    }
}
