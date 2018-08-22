<?php

namespace App\Http\Controllers;

use App\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
//        {
//            title: 'event1',
//                    description: 'lorem',
//                    start: '2018-06-15 12:00',
//                    end: '2018-06-15 12:30',
//                },
//        {
//            title: 'event2',
//                    description: 'lorem',
//                    start: '2018-06-15 12:00',
//                    end: '2018-06-15 12:30',
//                },
//        {
//            title: 'event3',
//                    description: 'lorem',
//                    start: '2018-06-15',
//                }

        return Booking::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'allDay' => 'boolean',
            'date' => 'date_format:Y-m-d',
            'time' => 'date_format:H:i',
        ], [], [
            'title' => 'Titel',
            'description' => 'Beskrivning',
            'date' => 'Datum',
            'time' => 'Tid',
        ]);

        Booking::create([
            'title' => $request->title,
            'description' => $request->description,
            'job_type_id' => $request->jobTypeId,
            'date' => $request->date,
            'time' => $request->time,
            'all_day' => $request->allDay,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Booking $booking, Request $request)
    {
        $booking->update([
            'date' => $request->get('date'),
            'time' => $request->get('allDay') ? null : $request->get('time'),
            'all_day' => $request->get('allDay')
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
    }
}
