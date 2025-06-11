@extends('layouts.app')

@section('title', __('Purchase Report'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Purchase Report') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    @include('report.include.__filter_purchase')
    <div class="card custom-card {{ $purchases->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($purchases->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                        <tr class="text-center">
                            <th scope="col">{{ __('Invoice No') }}</th>
                            <th scope="col">{{ __('Date') }}</th>
                            <th scope="col">{{ __('Supplier') }}</th>
                            <th scope="col">{{ __('WareHouse') }}</th>
                            <th scope="col">{{ __('Grand Total') }}</th>
                            <th scope="col">{{ __('Paid') }}</th>
                            <th scope="col">{{ __('Due') }}</th>
                            <th scope="col">{{ __('Return Amount') }}</th>
                            <th scope="col">{{ __('Return Due') }}</th>
                            <th scope="col">{{ __('Total Purchase Amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $total_purchase_amount = 0;
                        @endphp
                        @foreach ($purchases as $purchase)
                            <tr class="text-center">
                                <td>
                                    <strong>{{ $purchase->invoice_no ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $purchase->date }}</strong>
                                </td>
                                <td>
                                    {{ $purchase->supplier->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $purchase->wareHouse->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ showAmount($purchase->payable_amount) }}
                                </td>
                                <td>
                                    {{ showAmount($purchase->paying_amount) }}
                                </td>
                                <td>
                                    {{ showAmount($purchase->dueAmount()) }}
                                </td>
                                <td>
                                    {{ showAmount($purchase->return_amount) }}
                                </td>
                                <td>
                                    {{ showAmount($purchase->returnDueAmount()) }}
                                </td>
                                <td>
                                    {{ showAmount($purchase->totalPurchaseAmount()) }}
                                    @php
                                        $total_purchase_amount += $purchase->totalPurchaseAmount();
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="9" class="text-end">{{ __('Total') }}=</th>
                                <th class="text-center">{{ showAmount($total_purchase_amount) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $purchases->links('includes.__pagination') }}
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
