<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'subject',
        'message',
        'type', // 'contact' or 'quotation'
        'status', // 'pending', 'replied', 'closed'
    ];
}
