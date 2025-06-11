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
                            <strong>{{ __('Prime Stock Version') }}</strong>
                        </td>
                        <td>
                           1.0
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Laravel Version') }}</strong>
                        </td>
                        <td>
                            {{ app()->version() }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Database Driver') }}</strong>
                        </td>
                        <td>
                            {{ config('database.default') }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Environment') }}</strong>
                        </td>
                        <td>
                            {{ app()->environment() }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Cache Driver') }}</strong>
                        </td>
                        <td>
                            {{ config('cache.default') }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Session Driver') }}</strong>
                        </td>
                        <td>
                            {{ config('session.driver') }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Timezone') }}</strong>
                        </td>
                        <td>
                            {{ config('app.timezone') }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ __('Debug Mode') }}</strong>
                        </td>
                        <td>
                            {{ (config('app.debug') ? 'Enabled' : 'Disabled') }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
