@extends('layouts.app')

@section('title', __('Sale Report'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Sale Report') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    @include('report.include.__filter_sale')
    <div class="card custom-card {{ $sales->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($sales->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                        <tr class="text-center">
                            <th scope="col">{{ __('Invoice No') }}</th>
                            <th scope="col">{{ __('Date') }}</th>
                            <th scope="col">{{ __('Customer') }}</th>
                            <th scope="col">{{ __('WareHouse') }}</th>
                            <th scope="col">{{ __('Grand Total') }}</th>
                            <th scope="col">{{ __('Received') }}</th>
                            <th scope="col">{{ __('Due') }}</th>
                            <th scope="col">{{ __('Return Amount') }}</th>
                            <th scope="col">{{ __('Return Due') }}</th>
                            <th scope="col">{{ __('Total Sale Amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $total_sale_amount = 0;
                        @endphp
                        @foreach ($sales as $sale)
                            <tr class="text-center">
                                <td>
                                    <strong>{{ $sale->invoice_no ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $sale->date }}</strong>
                                </td>
                                <td>
                                    {{ $sale->customer->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $sale->wareHouse->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ showAmount($sale->receivable_amount) }}
                                </td>
                                <td>
                                    {{ showAmount($sale->received_amount) }}
                                </td>
                                <td>
                                    {{ showAmount($sale->dueAmount()) }}
                                </td>
                                <td>
                                    {{ showAmount($sale->return_amount) }}
                                </td>
                                <td>
                                    {{ showAmount($sale->returnDueAmount()) }}
                                </td>
                                <td>
                                    {{ showAmount($sale->totalSaleAmount()) }}
                                    @php
                                        $total_sale_amount += $sale->totalSaleAmount();
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="9" class="text-end">{{ __('Total') }}=</th>
                                <th class="text-center">{{ showAmount($total_sale_amount) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $sales->links('includes.__pagination') }}
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
