<?php

namespace App\Services\Solar;

use App\Models\ServiceSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class SolarSlotService
{
    public function __construct(
        private FcmDispatcher $fcm
    ) {}

    public function assignPendingSlot(ServiceSlot $slot, User $assignee): ServiceSlot
    {
        if ($slot->status !== ServiceSlot::STATUS_PENDING) {
            throw ValidationException::withMessages(['slot' => 'Only pending slots can be assigned.']);
        }

        if (! $assignee->isAdminUser()) {
            throw ValidationException::withMessages(['assignee' => 'Assignee must be an admin user.']);
        }

        $slot->update([
            'assigned_to' => $assignee->id,
            'assigned_at' => now(),
            'status' => ServiceSlot::STATUS_ASSIGNED,
        ]);

        $slot->load('user');
        $this->fcm->sendToUser(
            $slot->user,
            'Service scheduled',
            'A technician has been assigned to your visit on '.$slot->service_date->format('M d, Y').'.',
            ['type' => 'slot_assigned', 'slot_id' => (string) $slot->id]
        );

        return $slot->fresh(['assignedAdmin', 'user', 'subscription.package']);
    }

    public function completeWithCode(ServiceSlot $slot, User $admin, string $code): ServiceSlot
    {
        if (! in_array($slot->status, [ServiceSlot::STATUS_ASSIGNED, ServiceSlot::STATUS_PENDING], true)) {
            throw ValidationException::withMessages(['slot' => 'This slot cannot be completed.']);
        }

        $canManage = $admin->can('service_management');
        $isAssignee = (int) $slot->assigned_to === (int) $admin->id;

        if (! $canManage && ! $isAssignee) {
            throw ValidationException::withMessages(['slot' => 'You are not allowed to complete this visit.']);
        }

        if (strtoupper(trim($code)) !== strtoupper((string) $slot->verification_code)) {
            throw ValidationException::withMessages(['verification_code' => 'Invalid verification code.']);
        }

        $slot->update([
            'status' => ServiceSlot::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        $slot->load('user');
        $this->fcm->sendToUser(
            $slot->user,
            'Service completed',
            'Your solar service visit on '.$slot->service_date->format('M d, Y').' has been completed.',
            ['type' => 'service_completed', 'slot_id' => (string) $slot->id]
        );

        return $slot->fresh(['assignedAdmin', 'user', 'subscription.package']);
    }

    public function todayAssignmentsFor(User $admin): \Illuminate\Database\Eloquent\Collection
    {
        $today = Carbon::today();

        return ServiceSlot::query()
            ->where('assigned_to', $admin->id)
            ->whereDate('service_date', $today)
            ->with(['user', 'subscription.package', 'technicianReview'])
            ->orderBy('service_date')
            ->get();
    }

    /**
     * Slots assigned to this technician (for "My services" list / verification), scoped by date.
     */
    public function assignedSlotsForTechnician(User $admin, ?Carbon $dateFrom = null, ?Carbon $dateTo = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $from = ($dateFrom ?? Carbon::today())->startOfDay();
        $to = ($dateTo ?? Carbon::today()->copy()->addDays(14))->endOfDay();

        return ServiceSlot::query()
            ->where('assigned_to', $admin->id)
            ->whereIn('status', [ServiceSlot::STATUS_ASSIGNED, ServiceSlot::STATUS_COMPLETED])
            ->whereBetween('service_date', [$from, $to])
            ->with(['user:id,name,email,mobile,address', 'subscription.package', 'technicianReview'])
            ->orderBy('service_date')
            ->paginate(30)
            ->withQueryString();
    }
}
