<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['filename', 'section', 'package_id'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}

