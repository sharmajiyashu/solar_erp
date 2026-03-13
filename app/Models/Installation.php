<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installation extends Model
{
    protected $fillable = [
        'lead_id',
        'user_id',
        'installation_date',
        'status',
        'notes',
        'installation_done',
        'net_metering_pending',
        'net_metering_done',
        'second_tier_payment_received'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(InstallationAttachment::class);
    }
}
