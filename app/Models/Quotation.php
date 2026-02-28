<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'lead_id',
        'quotation_no',
        'quotation_date',
        'subtotal',
        'gst_amount',
        'total_amount',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
