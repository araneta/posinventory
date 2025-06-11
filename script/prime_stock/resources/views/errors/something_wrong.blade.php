@extends('layouts.app')

@section('title', __('Something Went Wrong'))

@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="text-center">
            <!--Page Widget Error-->
            <div class="card border-0 mb-4">
                <div class="card-body text-danger">
                    <div class="main-error-wrapper">
                        <i class="si si-close mb-4 fs-50"></i>
                        <h5 class="mb-4 text-danger">{{ session('error') }}</h5>
                        <a class="btn btn-outline-danger btn-sm" href="{{ route('dashboard') }}">{{ __('Dashboard')}}</a>
                    </div>
                </div>
            </div>
            <!--Page Widget Error-->
        </div>
    </div>
@endsection
