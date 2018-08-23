@extends('layouts.app')

@section('content')
    <div class="container">
        <print :title='{!! json_encode($title) !!}' :grouped_bookings='{!! json_encode($grouped_bookings) !!}'/>
    </div>
@endsection
