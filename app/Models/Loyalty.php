<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loyalty extends Model
{
    protected $fillable = ['customer_id', 'points_earned', 'points_redeemed', 'membership_tier', 'last_updated'];

    protected $table = 'loyalties';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

