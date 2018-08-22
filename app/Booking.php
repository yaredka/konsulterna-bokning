<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = ['job_type_color'];

    protected $dates = ['deleted_at'];

    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }

    public function getJobTypeColorAttribute()
    {
        return $this->jobType->primary_color;
    }
}
