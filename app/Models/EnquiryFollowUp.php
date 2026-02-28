<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryFollowUp extends Model
{
    protected $fillable = [
        'enquiry_id',
        'created_by',
        'followup_date',
        'followup_time',
        'remarks',
        'next_followup_date',
        'status'
    ];

    protected $casts = [
        'followup_date' => 'date',
        'next_followup_date' => 'date',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
