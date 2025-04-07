<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = ['customer_id', 'booking_id', 'subject', 'message', 'status', 'submitted_at', 'resolved_at', 'handled_by'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'handled_by');
    }
}

