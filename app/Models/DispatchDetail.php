<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispatchDetail extends Model
{
    protected $fillable = [
        'lead_id',
        'transporter_name',
        'vehicle_number',
        'driver_contact',
        'dispatch_date',
        'status',
        'challan_book'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
