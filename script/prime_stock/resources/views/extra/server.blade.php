@extends('layouts.app')

@section('title', __('Server Information'))
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Server Information') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    <div class="card custom-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <tbody>
                    <tr>
                        <td>
                            <strong>{{ __('PHP Version') }}</strong>
                        </td>
                        <td>
                            {{ phpversion() }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Server Software') }}</strong>
                        </td>
                        <td>
                            {{ $_SERVER['SERVER_SOFTWARE'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Server IP Address') }}</strong>
                        </td>
                        <td>
                            {{ $_SERVER['SERVER_ADDR'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Server Protocol') }}</strong>
                        </td>
                        <td>
                            {{ $_SERVER['SERVER_PROTOCOL'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('HTTP Host') }}</strong>
                        </td>
                        <td>
                            {{ $_SERVER['HTTP_HOST'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Server Port') }}</strong>
                        </td>
                        <td>
                            {{ $_SERVER['SERVER_PORT'] }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
