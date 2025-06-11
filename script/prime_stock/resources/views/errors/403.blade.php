@extends('layouts.error')

@section('title','Forbidden')

@section('content')
    <div class="notfound">
        <div class="notfound-404">
            <h1>403</h1>
        </div>
        <h2>{{ __('You are not authorized to access this page.') }}</h2>
        <p>{{ __('Please check your credentials and try again.') }}</p>
        <a href="{{ route('dashboard') }}">{{ __('Back to Homepage') }}</a>
    </div>
@endsection
