@extends('layouts.app')

@section('title', __('Purchase'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Purchase') }}</h5>
        </div>
        @can('create-purchase')
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="pe-1 mb-xl-0">
                    <a href="{{ route('purchase.create') }}" class="btn btn-primary label-btn">
                        <i class="ri-add-circle-line label-btn-icon me-2"></i>{{ __('Add New') }}
                    </a>
                </div>
            </div>
        @endcan
    </div>
    <!-- Page Header Close -->
    @include('purchase.include.__filter')
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
                                <th scope="col">{{ __('Invoice No|Date') }}</th>
                                <th scope="col">{{ __('Supplier|Mobile') }}</th>
                                <th scope="col">{{ __('Total Amount|Warehouse') }}</th>
                                <th scope="col">{{ __('Discount|Tax|Payable') }}</th>
                                <th scope="col">{{ __('Paid|Due') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $purchase)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $purchase->invoice_no }}</strong><br>
                                        <span>{{ $purchase->date }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $purchase->supplier->name }}</strong><br><span>{{ $purchase->supplier->phone }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($purchase->total_price) }}<br>
                                        <span class="text-primary">{{ $purchase->warehouse->name }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($purchase->discount) }} <br>
                                        {{ showAmount(taxAmount($purchase->total_price, $purchase->tax_amount)) }} <br>
                                        {{ showAmount($purchase->payable_amount) }}
                                    </td>
                                    <td>
                                        <strong>{{ showAmount($purchase->paying_amount) }}</strong><br>
                                        <span
                                            class="badge bg-danger-transparent">{{ showAmount($purchase->payable_amount - $purchase->paying_amount) }}</span>
                                    </td>

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @can('invoice-purchase')
                                                <a href="{{ route('purchase.show', $purchase->id) }}"
                                                    class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Invoice') }}"><i
                                                        class="ri-download-2-line"></i></a>
                                            @endcan
                                            @if ($purchase->status == 'due')
                                                @can('give-supplier-payment')
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-icon btn-sm btn-dark givePayment"
                                                        data-bs-target="#paymentModal" data-bs-toggle="modal"
                                                        data-id="{{ $purchase->id }}"
                                                        data-invoice="{{ $purchase->invoice_no }}"
                                                        data-amount="{{ $purchase->payable_amount }}"><i
                                                            class="ri-money-dollar-circle-fill" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="{{ __('Give Payment') }}"></i></a>
                                                @endcan
                                            @endif
                                            @can('create-purchase-return')
                                                <a href="{{ route('purchase.return', $purchase->id) }}"
                                                    class="btn btn-icon btn-sm btn-danger" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Return Purchase') }}"><i
                                                        class="ri-arrow-go-back-line"></i></a>
                                            @endcan
                                            @can('edit-purchase')
                                                <a href="{{ route('purchase.edit', $purchase->id) }}"
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
                {{ $purchases->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>

    @include('purchase.include.__give_payment')
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

                $('#invoice_no_modal').val(invoice)
                $('#payable_amount_modal').val(amount)
                $('#purchase_id_modal').val(id)

            })
        })
    </script>
@endpush
