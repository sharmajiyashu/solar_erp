<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDocument extends Model
{
    protected $fillable = [
        'lead_id',
        'document_type',
        'file_path',
        'status'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
