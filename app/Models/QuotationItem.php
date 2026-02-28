<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'item_name',
        'description',
        'quantity',
        'price',
        'total'
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
