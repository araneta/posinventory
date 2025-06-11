@extends('layouts.guest')
@section('title', __('Reset Password'))

@section('content')
    <div class="card-body">
        <h4 class="card-title">{{ __('Reset Password') }}</h4>
        <form action="{{ route('password.store') }}" method="POST" class="signin-form">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                @error('email')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="new-password">{{ __('New Password') }}</label>
                <input id="new-password" type="password" class="form-control" name="password" required autofocus data-eye>
                @error('password')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                @error('password_confirmation')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group m-0">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Reset Password') }}</button>
            </div>
        </form>
    </div>
@endsection
