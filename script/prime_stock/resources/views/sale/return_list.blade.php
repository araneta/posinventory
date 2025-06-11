@extends('layouts.app')

@section('title', __('Sale Return'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Sale Return') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    @include('sale.include.__filter')
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
                                <th scope="col">{{ __('Invoice No|Date') }}</th>
                                <th scope="col">{{ __('Customer|Mobile') }}</th>
                                <th scope="col">{{ __('Total Amount|Warehouse') }}</th>
                                <th scope="col">{{ __('Discount|Payable') }}</th>
                                <th scope="col">{{ __('Paid|Due') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $sale->invoice_no }}</strong><br>
                                        <span>{{ $sale->return_date }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $sale->customer->name }}</strong><br><span>{{ $sale->customer->phone }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($sale->return_amount) }}<br>
                                        <span class="text-primary">{{ $sale->warehouse->name }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($sale->return_discount) }} <br>
                                        {{ showAmount($sale->payable_amount) }}
                                    </td>
                                    <td>
                                        <strong>{{ showAmount($sale->paying_amount) }}</strong><br>
                                        <span
                                            class="badge bg-danger-transparent">{{ showAmount($sale->payable_amount - $sale->paying_amount) }}</span>
                                    </td>

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @can('invoice-sale-return')
                                                <a href="{{ route('sale.return.show', $sale->id) }}"
                                                    class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Invoice') }}"><i
                                                        class="ri-download-2-line"></i></a>
                                            @endcan
                                            @can('give-customer-payment')
                                                @if ($sale->paying_status == 'due')
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-icon btn-sm btn-dark receivePayment"
                                                        data-bs-target="#paymentModal" data-bs-toggle="modal"
                                                        data-id="{{ $sale->id }}" data-invoice="{{ $sale->invoice_no }}"
                                                        data-amount="{{ $sale->payable_amount }}"><i
                                                            class="ri-money-dollar-circle-fill" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="{{ __('Give Payment') }}"></i></a>
                                                @endif
                                            @endcan
                                            @can('edit-sale-return')
                                                <a href="{{ route('sale-return.edit', $sale->id) }}"
                                                    class="btn btn-icon btn-sm btn-info" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Edit') }}"><i
                                                        class="ri-edit-line"></i></a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $sales->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>

    @include('sale.include.__give_payment')
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
            //Give Payment Modal Click
            $(document).on('click', '.receivePayment', function() {
                let id = $(this).data('id');
                let invoice = $(this).data('invoice');
                let amount = $(this).data('amount');

                $('#invoice_no_modal').val(invoice)
                $('#payable_amount_modal').val(amount)
                $('#purchase_id_modal').val(id)

            })
        })
    </script>
@endpush
