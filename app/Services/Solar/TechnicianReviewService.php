<?php

namespace App\Services\Solar;

use App\Models\ServiceSlot;
use App\Models\TechnicianReview;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class TechnicianReviewService
{
    public function storeFromCustomer(User $customer, ServiceSlot $slot, int $rating, ?string $comment): TechnicianReview
    {
        if ((int) $slot->user_id !== (int) $customer->id) {
            throw ValidationException::withMessages(['slot' => 'This visit does not belong to your account.']);
        }

        if ($slot->status !== ServiceSlot::STATUS_COMPLETED) {
            throw ValidationException::withMessages(['slot' => 'You can only review after the visit is completed.']);
        }

        if (! $slot->assigned_to) {
            throw ValidationException::withMessages(['slot' => 'No technician was assigned to this visit.']);
        }

        if (TechnicianReview::where('service_slot_id', $slot->id)->exists()) {
            throw ValidationException::withMessages(['slot' => 'You have already submitted a review for this visit.']);
        }

        return TechnicianReview::create([
            'service_slot_id' => $slot->id,
            'user_id' => $customer->id,
            'technician_id' => $slot->assigned_to,
            'rating' => $rating,
            'comment' => $comment,
        ]);
    }
}
