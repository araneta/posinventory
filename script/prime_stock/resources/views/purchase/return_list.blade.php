@extends('layouts.app')

@section('title', __('Purchase Return'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Purchase Return') }}</h5>
        </div>
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
                                <th scope="col">{{ __('Lessed|Receivable') }}</th>
                                <th scope="col">{{ __('Received|Due') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $purchase)
                                <tr class="text-center">
                                    <td>
                                        <strong>{{ $purchase->invoice_no }}</strong><br>
                                        <span>{{ $purchase->return_date }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $purchase->supplier->name }}</strong><br><span>{{ $purchase->supplier->phone }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($purchase->return_amount) }}<br>
                                        <span class="text-primary">{{ $purchase->warehouse->name }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($purchase->return_discount) }} <br>
                                        {{ showAmount($purchase->receivable_amount) }}
                                    </td>
                                    <td>
                                        <strong>{{ showAmount($purchase->received_amount) }}</strong><br>
                                        <span
                                            class="badge bg-danger-transparent">{{ showAmount($purchase->receivable_amount - $purchase->received_amount) }}</span>
                                    </td>

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            @can('invoice-purchase-return')
                                                <a href="{{ route('purchase.return.show', $purchase->id) }}"
                                                    class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Invoice') }}"><i
                                                        class="ri-download-2-line"></i></a>
                                            @endcan
                                            @can('receive-supplier-payment')
                                                @if ($purchase->return_status == 'due')
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-icon btn-sm btn-dark receivePayment"
                                                        data-bs-target="#paymentModal" data-bs-toggle="modal"
                                                        data-id="{{ $purchase->id }}"
                                                        data-invoice="{{ $purchase->invoice_no }}"
                                                        data-amount="{{ $purchase->receivable_amount }}"><i
                                                            class="ri-money-dollar-circle-fill" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ __('Receive Payment') }}"></i></a>
                                                @endif
                                            @endcan
                                            @can('edit-purchase-return')
                                                <a href="{{ route('purchase-return.edit', $purchase->id) }}"
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

    @include('purchase.include.__receive_payment')
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
                $('#receivable_amount_modal').val(amount)
                $('#purchase_id_modal').val(id)

            })
        })
    </script>
@endpush
