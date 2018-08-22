<?php

namespace App\Http\Controllers;

use App\JobType;
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
}
