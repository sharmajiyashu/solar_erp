<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solar\AssignSlotRequest;
use App\Http\Requests\Solar\CompleteSlotRequest;
use App\Models\ServiceSlot;
use App\Models\User;
use App\Services\Solar\SolarSlotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSolarSlotsApiController extends Controller
{
    public function __construct(
        private SolarSlotService $slotService
    ) {
        $this->middleware(['auth', 'isAdmin']);
    }

    public function index(Request $request): JsonResponse
    {
        if (! $request->user()->can('service_assign')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $slots = ServiceSlot::query()
            ->where('status', ServiceSlot::STATUS_PENDING)
            ->with(['user:id,name,email,mobile', 'subscription.package'])
            ->orderBy('service_date')
            ->get();

        return response()->json(['data' => $slots]);
    }

    public function assign(AssignSlotRequest $request): JsonResponse
    {
        $slot = ServiceSlot::findOrFail($request->slot_id);
        $assignee = User::findOrFail($request->assigned_to);

        try {
            $updated = $this->slotService->assignPendingSlot($slot, $assignee);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        }

        return response()->json(['status' => true, 'data' => $updated]);
    }

    public function complete(CompleteSlotRequest $request): JsonResponse
    {
        $slot = ServiceSlot::findOrFail($request->slot_id);

        if (! $request->user()->can('service_management')
            && (int) $slot->assigned_to !== (int) $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        try {
            $updated = $this->slotService->completeWithCode($slot, $request->user(), $request->verification_code);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }

        return response()->json(['status' => true, 'data' => $updated]);
    }
}
