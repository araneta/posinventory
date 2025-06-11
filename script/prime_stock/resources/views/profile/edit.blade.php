@extends('layouts.app')

@section('title', __('Profile Settings'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Profile Settings') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title" id="profile">{{ __('Personal Information') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" class="row g-3" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label fs-14 text-dark">{{ __('Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                       value="{{ auth()->user()->name }}" placeholder="Enter your name">
                                @error('name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label fs-14 text-dark">{{ __('Email') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                       value="{{ auth()->user()->email }}" placeholder="Enter your email">
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title" id="profile">{{ __('Update Password') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('password.update') }}" class="row g-3" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="current_password" class="form-label fs-14 text-dark">{{ __('Current Password') }} <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Enter current password">
                                @error('current_password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="password" class="form-label fs-14 text-dark">{{ __('New Password') }} <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter new password">
                                @error('password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fs-14 text-dark">{{ __('Confirm Password') }} <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Enter confirm password">
                                @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Update Password') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
