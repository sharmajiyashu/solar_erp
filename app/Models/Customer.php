<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_code',
        'name',
        'mobile',
        'alternate_mobile',
        'email',
        'address',
        'city',
        'state',
        'pincode',
        'created_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Customer created by admin/user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Customer leads
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
