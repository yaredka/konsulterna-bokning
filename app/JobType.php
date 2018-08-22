<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $guarded = [];

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }
}
