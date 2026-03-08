<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallationAttachment extends Model
{
    protected $fillable = [
        'installation_id',
        'file'
    ];

    public function installation()
    {
        return $this->belongsTo(Installation::class);
    }
}
