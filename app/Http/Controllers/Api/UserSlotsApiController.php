<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceSlot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSlotsApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request): JsonResponse
    {
        $slots = ServiceSlot::query()
            ->where('user_id', Auth::id())
            ->with(['assignedAdmin:id,name,email,mobile', 'subscription.package', 'technicianReview'])
            ->orderByDesc('service_date')
            ->get()
            ->map(fn (ServiceSlot $s) => [
                'id' => $s->id,
                'service_date' => $s->service_date?->toDateString(),
                'status' => $s->status,
                'verification_code' => $s->verification_code,
                'assigned_admin' => $s->assignedAdmin ? [
                    'id' => $s->assignedAdmin->id,
                    'name' => $s->assignedAdmin->name,
                    'email' => $s->assignedAdmin->email,
                    'mobile' => $s->assignedAdmin->mobile,
                ] : null,
                'plan' => $s->subscription?->package?->name,
                'customer_review_of_technician' => $s->technicianReview ? [
                    'rating' => $s->technicianReview->rating,
                    'comment' => $s->technicianReview->comment,
                ] : null,
            ]);

        return response()->json(['data' => $slots]);
    }
}
