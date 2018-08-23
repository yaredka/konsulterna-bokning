<?php

namespace App\Http\Controllers;

use App\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function index(Request $request)
    {
        return Booking::filterByRequest($request)->get();
    }

    public function store(Request $request)
    {
        Booking::validate($request);

        $booking = Booking::create([
            'title' => $request->title,
            'description' => $request->description,
            'job_type_id' => $request->jobTypeId,
            'date' => $request->date,
            'time' => $request->time,
            'all_day' => $request->allDay,
        ]);

        return $booking;
    }

    public function update(Booking $booking, Request $request)
    {
        Booking::validate($request);

        $booking->update([
            'title' => $request->title,
            'description' => $request->description,
            'job_type_id' => $request->jobTypeId,
            'date' => $request->date,
            'time' => $request->time,
            'all_day' => $request->allDay,
        ]);

        return $booking;
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return $booking;
    }
}
