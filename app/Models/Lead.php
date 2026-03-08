<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'lead_no',
        'enquiry_id',
        'customer_id',
        'created_by',
        'stage',
        'status',
        'project_stages',
        'remarks'
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'project_stages' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Lead belongs to customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Lead assigned to user
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Lead created by user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // If lead is converted from enquiry
    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function visits()
    {
        return $this->hasMany(SiteVisit::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function bankDocuments()
    {
        return $this->hasMany(BankDocument::class);
    }

    public function dispatchDetail()
    {
        return $this->hasOne(DispatchDetail::class);
    }

    public function installation()
    {
        return $this->hasOne(Installation::class);
    }

    public function verificationReport()
    {
        return $this->hasOne(VerificationReport::class);
    }

    public static $workflowStages = [
        'site_visit',
        'quotation',
        'bank',
        'discom',
        'dispatch',
        'installation',
        'verification',
        'completed'
    ];
}
