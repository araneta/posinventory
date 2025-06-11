@extends('layouts.guest')
@section('title', __('Login'))

@section('content')
    <div class="card-body">
        <h4 class="card-title">{{ __('Sign In') }}</h4>
        @if (session('error'))
            <p class="text-danger">{{ session('error') }}</p>
        @endif
        <form action="{{ route('login') }}" method="POST" class="signin-form">
            @csrf
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    placeholder="Email" required autofocus autocomplete="username" value="@if (appMode() == 'demo'){{ 'admin@gmail.com' }}@else{{ old('email') }}@endif">
                @error('email')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('Password') }}
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="float-right">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" equired autocomplete="current-password" value="@if (appMode() == 'demo'){{ 'password' }}@endif" placeholder="Password" data-eye>
                @error('password')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <div class="custom-checkbox custom-control">
                    <input type="checkbox" name="remember_me" id="remember" class="custom-control-input">
                    <label for="remember" class="custom-control-label">{{ __('Remember Me') }}</label>
                </div>
            </div>

            <div class="form-group m-0">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Sign In') }}
                </button>
            </div>
        </form>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('#copy_btn').on('click', function() {
                var email = $('tbody tr th').text().trim();
                var password = $('tbody tr td:first').text().trim();
                $('input[name="email"]').val(email);
                $('input[name="password"]').val(password);

                // Submit the form
                $('form').submit();
            });
        });
    </script>
@endpush
