@extends('layouts.error')

@section('title','Page Not Found')

@section('content')    
    <div class="notfound">
        <div class="notfound-404">
            <h1>500</h1>
        </div>
        <h2>{{ __('An unexpected error occurred. Please try again later.') }}</h2>
        <a href="{{ route('dashboard') }}">{{ __('Back to Homepage') }}</a>
    </div>
@endsection
