<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationReport extends Model
{
    protected $fillable = [
        'lead_id',
        'verified_by',
        'verified_by_manual',
        'verification_date',
        'is_docs_proceed_for_2nd_tranch',
        'status',
        'second_tier_payment_received',
        'is_subsidy_received',
        'is_verified',
        'quotation_price',
        'first_tranche_date',
        'second_tranche_amount',
        'tax_invoice_amount',
        'payout_amount',
        'remarks'
    ];

    protected $casts = [
        'first_tranche_date' => 'date',
        'verification_date' => 'date',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
