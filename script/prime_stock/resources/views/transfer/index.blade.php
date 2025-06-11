@extends('layouts.app')

@section('title', __('Transfer'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Transfer') }}</h5>
        </div>
        @can('create-transfer')
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="pe-1 mb-xl-0">
                    <a class="btn btn-primary label-btn" href="{{ route('transfer.create') }}">
                        <i class="ri-add-circle-line label-btn-icon me-2"></i>{{ __('Add New') }}
                    </a>
                </div>
            </div>
        @endcan
    </div>
    <!-- Page Header Close -->

    <div class="card custom-card {{ $transfers->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($transfers->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Tracking No') }}</th>
                                <th scope="col">{{ __('Date') }}</th>
                                <th scope="col">{{ __('From Warehouse') }}</th>
                                <th scope="col">{{ __('To Warehouse') }}</th>
                                <th scope="col">{{ __('Product') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transfers as $transfer)
                                <tr>
                                    <td>
                                        <strong>{{ $transfer->tracking_no }}</strong>
                                    </td>
                                    <td>
                                        {{ $transfer->date }}
                                    </td>
                                    <td>
                                        {{ $transfer->fromWarehouse->name }}
                                    </td>
                                    <td>
                                        {{ $transfer->toWarehouse->name }}
                                    </td>
                                    <td>
                                        {{ $transfer->total_product }}
                                    </td>
                                    <td>
                                        @can('edit-transfer')
                                            <div class="hstack gap-2 flex-wrap">
                                                <a class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                    href="{{ route('transfer.edit', $transfer->id) }}"><i class="ri-edit-line"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit') }}"></i></a>
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $transfers->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>


@endsection
@push('script')
    <script>
        $(function() {
            "use strict"
            var options = {
                'position': 'top-right'
            }
            var notifier = new AWN(options);
        })
    </script>
@endpush
