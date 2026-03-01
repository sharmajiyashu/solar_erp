<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installation extends Model
{
    protected $fillable = [
        'lead_id',
        'user_id',
        'installation_date',
        'status',
        'notes'
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
