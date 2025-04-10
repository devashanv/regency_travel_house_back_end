<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['name', 'country', 'region', 'description', 'thumbnail_url', 'best_time_to_visit'];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}

