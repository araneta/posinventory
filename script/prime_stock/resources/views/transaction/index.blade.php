@extends('layouts.app')

@section('title', __('Transaction'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Transaction') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    @can('filter-transaction')        
        @include('transaction.__filter')
    @endcan
    <div class="card custom-card {{ $transactions->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                        <tr class="text-center">
                            <th scope="col">{{ __('Trx No') }}</th>
                            <th scope="col">{{ __('Account') }}</th>
                            <th scope="col">{{ __('Amount') }}</th>
                            <th scope="col">{{ __('Date') }}</th>
                            <th scope="col">{{ __('Type') }}</th>
                            <th scope="col">{{ __('Notes') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($transactions as $transaction)
                            <tr class="text-center">
                                <td>
                                    <strong>{{ $transaction->transaction_id }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $transaction->account->name }}</strong>
                                </td>
                                <td>
                                    {{ showAmount($transaction->amount) }}
                                </td>
                                <td>
                                    {{ $transaction->date }}
                                </td>
                                <td>
                                    <span
                                        class="badge bg-info-transparent">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $transaction->notes }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $transactions->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>

@endsection
@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        $(function() {
            "use strict"
            $('.js-example-basic-single').select2();
            flatpickr("#date", {
                mode: "range",
                dateFormat: "Y-m-d",
            });
        })
    </script>
@endpush
