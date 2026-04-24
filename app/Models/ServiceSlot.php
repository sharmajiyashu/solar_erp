<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceSlot extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_ASSIGNED = 'assigned';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_MISSED = 'missed';

    protected $fillable = [
        'subscription_id',
        'user_id',
        'service_date',
        'status',
        'verification_code',
        'assigned_to',
        'assigned_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'service_date' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(ServiceFeedback::class, 'service_slot_id');
    }

    /** Rating & comment left by the customer (user) for the assigned technician after completion. */
    public function technicianReview(): HasOne
    {
        return $this->hasOne(TechnicianReview::class, 'service_slot_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'service_slot_id');
    }
}
