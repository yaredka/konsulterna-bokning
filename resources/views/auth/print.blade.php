@extends('layouts.app')

@section('content')
    <div class="container">
        <print :title='@json($title)' :grouped_bookings='@json($grouped_bookings)'/>
    </div>
@endsection
