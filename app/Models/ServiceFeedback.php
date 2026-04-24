<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceFeedback extends Model
{
    protected $table = 'service_feedback';

    protected $fillable = [
        'service_slot_id',
        'admin_id',
        'rating',
        'comment',
    ];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(ServiceSlot::class, 'service_slot_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
