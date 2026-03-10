<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'enquiry_no',
        'customer_name',
        'mobile',
        'alternate_mobile',
        'email',
        'address',
        'city',
        'state',
        'pincode',
        'source',
        'created_by',
        'status',
        'next_followup_date',
        'remarks',
        'solar_type',
        'price_quote',
        'project_size',
    ];

    protected $casts = [
        'next_followup_date' => 'date',
    ];

    public function followUps()
    {
        return $this->hasMany(EnquiryFollowUp::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
