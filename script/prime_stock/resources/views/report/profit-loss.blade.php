@extends('layouts.app')

@section('title', __('Profit Loss Report'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Profit Loss Report') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    @include('report.include.__filter_profit_loss')
    <div class="card custom-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                    <tr class="text-center">
                        <th scope="col">{{ __('SN') }}</th>
                        <th scope="col">{{ __('Type') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>
                                <strong>{{ __('1') }}</strong>
                            </td>
                            <td>
                                <strong>{{ __('Total Sale') }}</strong>
                            </td>
                            <td>
                                {{ showAmount($totalSale) }}
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                <strong>{{ __('2') }}</strong>
                            </td>
                            <td>
                                <strong>{{ __('Total Purchase') }}</strong>
                            </td>
                            <td>
                                {{ showAmount($totalPurchase) }}
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                <strong>{{ __('3') }}</strong>
                            </td>
                            <td>
                                <strong>{{ __('Tax') }}</strong>
                            </td>
                            <td>
                                {{ showAmount($totalTax) }}
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                <strong>{{ __('4') }}</strong>
                            </td>
                            <td>
                                <strong>{{ __('Discount') }}</strong>
                            </td>
                            <td>
                                {{ showAmount($totalDiscount) }}
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                <strong>{{ __('5') }}</strong>
                            </td>
                            <td>
                                <strong>{{ __('Sale Return') }}</strong>
                            </td>
                            <td>
                                {{ showAmount($totalSaleReturn) }}
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                <strong>{{ __('6') }}</strong>
                            </td>
                            <td>
                                <strong>{{ __('Purchase Return') }}</strong>
                            </td>
                            <td>
                                {{ showAmount($totalPurchaseReturn) }}
                            </td>
                        </tr>
                        <tr class="text-center {{ $grossProfit < 0 ? 'table-danger' : 'table-success' }}">
                            <td>
                                <strong>{{ __('7') }}</strong>
                            </td>
                            <td>
                                <strong>{{ __('Gross Profit') }}</strong>
                            </td>
                            <td>
                                {{ showAmount($grossProfit) }}
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                <strong>{{ __('8') }}</strong>
                            </td>
                            <td>
                                <strong>{{ __('Expense') }}</strong>
                            </td>
                            <td>
                                {{ showAmount($totalExpense) }}
                            </td>
                        </tr>
                        <tr class="text-center {{ $netProfit < 0 ? 'table-danger' : 'table-success' }}">
                            <td>
                                <strong>{{ __('9') }}</strong>
                            </td>
                            <td>
                                <strong>{{ __('Net Profit') }}</strong>
                            </td>
                            <td>
                                {{ showAmount($netProfit) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        $(function () {
            "use strict"
            $('.js-example-basic-single').select2();
            flatpickr("#date", {
                mode: "range",
                dateFormat: "Y-m-d",
            });
        })
    </script>
@endpush
