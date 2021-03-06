<?php

namespace App;
use Illuminate\Http\Request;
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

    public function scopeFilterByRequest($q, $request) : void
    {
        if ($start = $request->input('start')) {
            $q->whereDate('date', '>=', $start);
        }
        if ($end = $request->input('end')) {
            $q->whereDate('date', '<=', $end);
        }
        if ($date = $request->input('date')) {
            $q->whereDate('date', '=', $date);
        }
    }

    public function scopeWithJobType($q) {
        $q->join(
            'job_types',
            'bookings.job_type_id',
            '=',
            'job_types.id'
        );
    }

    public static function validate(Request $request) {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'allDay' => 'boolean',
            'date' => 'date_format:Y-m-d',
            'time' => 'nullable|date_format:H:i',
        ], [], [
            'title' => 'Titel',
            'description' => 'Beskrivning',
            'date' => 'Datum',
            'time' => 'Tid',
        ]);
    }
}
