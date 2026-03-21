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
        'remarks',
        'lead_type',
        'first_payment_received',
        'token_amount',
        'is_document_done',
        'discom_pms_portal_login_done',
        'bank_login_done',
        'handover_by',
        'cancellation_reason',
        'cancelled_at'
    ];

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {

            $q->where('lead_no', 'like', "%{$search}%")
                ->orWhere('remarks', 'like', "%{$search}%")

                ->orWhereHas('customer', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");
                });
        });
    }

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

    public function documents()
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
        'document',
        'backend',
        'procurement',
        'installation',
        'verification',
        'completed'
    ];
}
