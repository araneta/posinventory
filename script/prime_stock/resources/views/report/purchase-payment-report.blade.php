@extends('layouts.app')

@section('title', __('Purchase Payment Report'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Purchase Payment Report') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    @include('report.include.__filter_payment_purchase')
    <div class="card custom-card {{ $payments->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                        <tr class="text-center">
                            <th scope="col">{{ __('Invoice No') }}</th>
                            <th scope="col">{{ __('Date') }}</th>
                            <th scope="col">{{ __('Warehouse') }}</th>
                            <th scope="col">{{ __('Supplier') }}</th>
                            <th scope="col">{{ __('TRX') }}</th>
                            <th scope="col">{{ __('Account') }}</th>
                            <th scope="col">{{ __('Amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($payments as $payment)
                            <tr class="text-center">
                                <td>
                                    <strong>{{ $payment->purchase->invoice_no ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $payment->date }}</strong>
                                </td>
                                <td>
                                    {{ $payment->purchase->warehouse->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $payment->purchase->supplier->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $payment->transaction_id }}
                                </td>
                                <td>
                                    {{ $payment->account->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ showAmount($payment->amount) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="text-center">
                            <td colspan="6" class="text-end">
                                <strong>{{ __('Total') }}</strong>
                            </td>
                            <td>
                                <strong>{{ showAmount($payments->sum('amount')) }}</strong>
                            </td>
                        </tfoot>
                    </table>
                </div>
                {{ $payments->links('includes.__pagination') }}
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
