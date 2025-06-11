@extends('layouts.app')

@section('title', __('Sale'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Sale') }}</h5>
        </div>
        @can('create-sale')
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="pe-1 mb-xl-0">
                    <a href="{{ route('sale.create') }}" class="btn btn-primary label-btn">
                        <i class="ri-add-circle-line label-btn-icon me-2"></i>{{ __('Add New') }}
                    </a>
                </div>
            </div>
        @endcan
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
                                <th scope="col">{{ __('Discount|Tax|Receivable') }}</th>
                                <th scope="col">{{ __('Received|Due') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $sale->invoice_no }}</strong><br>
                                        <span>{{ $sale->date }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $sale->customer->name }}</strong><br><span>{{ $sale->customer->phone }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($sale->total_price) }}<br>
                                        <span class="text-primary">{{ $sale->warehouse->name }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($sale->discount) }} <br>
                                        {{ showAmount(taxAmount($sale->total_price, $sale->tax_amount)) }} <br>
                                        {{ showAmount($sale->receivable_amount) }}
                                    </td>
                                    <td>
                                        <strong>{{ showAmount($sale->received_amount) }}</strong><br>
                                        <span
                                            class="badge bg-danger-transparent">{{ showAmount($sale->receivable_amount - $sale->received_amount) }}</span>
                                    </td>

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @can('invoice-sale')
                                                <a href="{{ route('sale.show', $sale->id) }}"
                                                    class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Invoice') }}"><i
                                                        class="ri-download-2-line"></i></a>
                                            @endcan
                                            @can('receive-customer-payment')
                                                @if ($sale->status == 'due')
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-icon btn-sm btn-dark givePayment"
                                                        data-bs-target="#paymentModal" data-bs-toggle="modal"
                                                        data-id="{{ $sale->id }}" data-invoice="{{ $sale->invoice_no }}"
                                                        data-amount="{{ $sale->receivable_amount }}"><i
                                                            class="ri-money-dollar-circle-fill" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ __('Received Payment') }}"></i></a>
                                                @endif
                                            @endcan
                                            @can('create-sale-return')
                                                <a href="{{ route('sale.return', $sale->id) }}"
                                                    class="btn btn-icon btn-sm btn-danger" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Return Sale') }}"><i
                                                        class="ri-arrow-go-back-line"></i></a>
                                            @endcan
                                            @can('edit-sale')
                                                <a href="{{ route('sale.edit', $sale->id) }}"
                                                    class="btn btn-icon btn-sm btn-info" data-bs-toggle="tooltip"
                                                    class="ri-edit-line"><i
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

    @include('sale.include.__receive_payment')
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
            $(document).on('click', '.givePayment', function() {
                let id = $(this).data('id');
                let invoice = $(this).data('invoice');
                let amount = $(this).data('amount');
                console.log(id, invoice, amount)
                $('#invoice_no_modal').val(invoice)
                $('#receivable_amount_modal').val(amount)
                $('#purchase_id_modal').val(id)

            })
        })
    </script>
@endpush
