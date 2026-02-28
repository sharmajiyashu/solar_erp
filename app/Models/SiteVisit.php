<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    protected $fillable = [
        'lead_id',
        'user_id',
        'visit_date',
        'roof_type',
        'shadow_analysis',
        'suggested_capacity',
        'notes',
        'status'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
