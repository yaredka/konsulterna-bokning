<?php

namespace App\Http\Controllers;

use App\JobType;
use App\Booking;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->check()) {
            $jobTypes = JobType::all();

            return view('auth.dashboard', compact('jobTypes'));
        }

        return view('auth.login');
    }

    public function print(Request $request)
    {
        if (auth()->check() && !empty($request->input('date'))) {
            $bookings = Booking::filterByRequest($request)
                            ->withJobType()
                            ->get();

            $grouped_bookings = collect($bookings)
                            ->sortBy('time')
                            ->groupBy('time')
                            ->toArray();

            $title = $request->input('date');

            return view('auth.print', compact('grouped_bookings', 'title'));
        }

        return view('auth.login');
    }
}
