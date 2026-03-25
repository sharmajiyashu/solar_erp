<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSlot extends Model
{
    protected $fillable = [
        'subscription_id',
        'service_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'service_date' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }
}
