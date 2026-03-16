<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'punch_in',
        'punch_out',
        'punch_in_photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
