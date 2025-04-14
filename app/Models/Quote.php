<?php

namespace App\Models;
use App\Models\Staff;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = ['customer_id', 'package_id', 'number_of_people', 'start_date', 'end_date', 'special_requests', 'estimated_price', 'status', 'responded_by'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /*public function destination()
    {
        return $this->belongsTo(Destination::class);
    }*/
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'responded_by');
    }

    public function respondedBy()
    {
        return $this->belongsTo(Staff::class, 'responded_by');
    }
}

