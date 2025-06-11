@extends('layouts.error')

@section('title', 'Not Found')

@section('content')
    <div class="notfound">
        <div class="notfound-404">
            <h1>404</h1>
        </div>
        <h2>{{ __('Oopps. The page you were looking for doesn\'t exist.') }}</h2>
        <p>{{ __('You may have mistyped the address or the page may have moved.') }}</p>
        <a href="{{ route('dashboard') }}">{{ __('Back to Homepage') }}</a>
    </div>
@endsection
