<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solar\AssignSlotRequest;
use App\Http\Requests\Solar\CompleteSlotRequest;
use App\Models\ServiceSlot;
use App\Models\User;
use App\Services\Solar\SolarSlotService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SolarSlotAdminController extends Controller
{
    public function __construct(
        private SolarSlotService $slotService
    ) {
        $this->middleware(['auth', 'isAdmin']);
    }

    public function pendingSlots(Request $request): View
    {
        $this->authorizeSlotAssign($request);

        $slots = ServiceSlot::query()
            ->where('status', ServiceSlot::STATUS_PENDING)
            ->with(['user', 'subscription.package'])
            ->orderBy('service_date')
            ->paginate(25);

        $admins = User::query()->where('role', User::$admin)->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.solar.slots_pending', compact('slots', 'admins'));
    }

    public function assignSlot(AssignSlotRequest $request)
    {
        $slot = ServiceSlot::findOrFail($request->slot_id);
        $assignee = User::findOrFail($request->assigned_to);

        try {
            $this->slotService->assignPendingSlot($slot, $assignee);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->back()->with('success', 'Slot assigned successfully.');
    }

    public function myServices(Request $request): View
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from) : null;
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to) : null;

        $slots = $this->slotService->assignedSlotsForTechnician($request->user(), $dateFrom, $dateTo);

        return view('admin.solar.my_services', compact('slots'));
    }

    public function completeForm(Request $request, ServiceSlot $slot): View
    {
        $this->authorizeComplete($request, $slot);

        return view('admin.solar.complete_slot', compact('slot'));
    }

    public function completeSlot(CompleteSlotRequest $request)
    {
        $slot = ServiceSlot::findOrFail($request->slot_id);
        $this->authorizeComplete($request, $slot);

        try {
            $this->slotService->completeWithCode($slot, $request->user(), $request->verification_code);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('admin.solar.my_services')
            ->with('success', 'Visit marked complete. The customer can rate the technician from their account.');
    }

    private function authorizeSlotAssign(Request $request): void
    {
        if (! $request->user()->can('service_assign')) {
            abort(403);
        }
    }

    private function authorizeComplete(Request $request, ServiceSlot $slot): void
    {
        $u = $request->user();
        if ($u->can('service_management')) {
            return;
        }
        if ((int) $slot->assigned_to === (int) $u->id) {
            return;
        }
        abort(403);
    }
}
