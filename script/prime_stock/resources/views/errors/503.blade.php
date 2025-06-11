@extends('layouts.error')

@section('title','Under Maintenance')

@section('content')
    <div class="notfound">
        <div class="notfound-404">
            <h1>503</h1>
        </div>
        <h2>{{ __('The server is currently unavailable. Please try again later.') }}</h2>
        <p>{{ __('Our team is working on it. Please check back later.') }}</p>
        <a href="{{ route('dashboard') }}">{{ __('Back to Homepage') }}</a>
    </div>
@endsection
