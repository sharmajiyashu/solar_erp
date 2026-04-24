<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicianReview extends Model
{
    protected $fillable = [
        'service_slot_id',
        'user_id',
        'technician_id',
        'rating',
        'comment',
    ];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(ServiceSlot::class, 'service_slot_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
