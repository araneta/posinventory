@extends('layouts.guest')
@section('title', __('Forgot Password'))

@section('content')
    <div class="card-body">
        <h4 class="card-title">{{ __('Forgot Password?') }}</h4>
        <form action="{{ route('password.email') }}" method="POST" class="signin-form">
            @csrf
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Email">
                @error('email')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
                <div class="form-text text-muted">
                    By clicking "Reset Password" we will send a password reset link
                </div>
            </div>

            <div class="form-group m-0">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Reset Password') }}</button>
            </div>
        </form>
    </div>
@endsection
