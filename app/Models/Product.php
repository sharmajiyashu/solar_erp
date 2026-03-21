<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subtype',
        'company',
        'total_landing_wo_gst',
        'gst_percentage',
        'tax_amount',
        'final_landing_with_gst',
        'three_kw_dcr_qnt',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
