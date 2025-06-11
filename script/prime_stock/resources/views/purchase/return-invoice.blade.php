@extends('layouts.app')

@section('title', __('Purchase Return Details'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Purchase Return Details') }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="{{ route('purchase-return.index') }}" class="btn btn-danger label-btn">
                    <i class="ri-arrow-go-back-line label-btn-icon me-2"></i>{{ __('Back') }}
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Purchase Details -->
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="main-content-body-invoice">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="invoice-header">
                            <h2 class="invoice-title">{{ __('Invoice') }} #{{ $purchase->invoice_no }}</h2>
                            {!! generateQRCode(route('purchase.return.show', $purchase->id)) !!}
                            <div class="billed-from">
                                <h6>{{ settings('company_name') }}</h6>
                                <p>{{ settings('company_address') }}<br>
                                    {{ __('Tel No') }}: {{ settings('company_phone') }}<br>
                                    {{ __('Email') }}: {{ settings('company_email') }}</p>
                            </div><!-- billed-from -->
                        </div><!-- invoice-header -->
                        <div class="row mt-4">
                            <div class="col-md">
                                <label class="text-gray-6">{{ __('Billed To') }}</label>
                                <div class="billed-to">
                                    <h6 class="fs-14 fw-semibold">{{ $purchase->supplier->name }}</h6>
                                    <p>{{ $purchase->supplier->address }}<br>
                                        {{ __('Tel No') }}: {{ $purchase->supplier->phone }}<br>
                                        {{ __('Email') }}: {{ $purchase->supplier->email }}</p>
                                </div>
                            </div>
                            <div class="col-md">
                                <label class="text-gray-6">{{ __('Invoice Information') }}</label>
                                <p class="invoice-info-row"><span>{{ __('Invoice No') }}</span> <span>{{ $purchase->invoice_no }}</span></p>
                                <p class="invoice-info-row"><span>{{ __('Warehouse') }}</span> <span>{{ $purchase->warehouse->name ?? 'N/A' }}</span></p>
                                <p class="invoice-info-row"><span>{{ __('Return Date') }}:</span> <span>{{ $purchase->return_date ?? 'N/A' }}</span></p>
                                <p class="invoice-info-row"><span>{{ __('Return Status') }}:</span> <span>{{ $purchase->return_status ?? 'N/A' }}</span></p>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="w-20">{{ __('Product') }}</th>
                                    <th class="w-40">{{ __('Code') }}</th>
                                    <th class="text-center">{{ __('Quantity') }}</th>
                                    <th>{{ __('Unit Price') }}</th>
                                    <th>{{ __('Total Amount') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($purchase->returns as $product)
                                    <tr>
                                        <td>{{ $product->product->name }}
                                            @if($product->product->product_type == 'variation')
                                                <p class="text-muted mb-0">{{ variationName($product->variation_id) }}: {{ $product->variation_value }}</p>
                                            @endif
                                        </td>
                                        <td>{{ $product->product->code }}</td>
                                        <td class="text-center">{{ $product->return_quantity }} {{ $product->product->unit->name }}</td>
                                        <td>{{ showAmount($product->return_price) }}</td>
                                        <td>{{ showAmount($product->total_return_price) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="align-top" colspan="2" rowspan="4">
                                        <div class="invoice-notes">
                                            <label class="main-content-label fs-13">{{ __('Notes') }}</label>
                                            <p class="fw-normal fs-13">{{ $purchase->return_note }}</p>
                                        </div><!-- invoice-notes -->
                                    </td>
                                    <td>{{ __('Sub-Total') }}</td>
                                    <td colspan="2">{{ showAmount($purchase->return_amount) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Discount') }}</td>
                                    <td colspan="2">-{{ showAmount($purchase->return_discount) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Grand Total') }}</td>
                                    <td colspan="2">{{ showAmount($purchase->receivable_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class=" text-uppercase tx-inverse">{{ __('Total Due') }}</td>
                                    <td colspan="2">
                                        <h4 class="text-danger">{{ showAmount($purchase->returnDueAmount()) }}</h4>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <a href="javascript:void(0);" class="btn btn-danger float-end mt-3 ms-2 btn_print">
                            <i class="mdi mdi-printer me-1"></i>{{ __('Print') }}
                        </a>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
    <!--End::row-1 -->
@endsection
@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script>
        $(function () {
            "use strict"
            // print function to print the invoice
            $('.btn_print').on('click', function () {
                let printContents = $('.card-invoice').html();
                let originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
            });

            /**
             * if print window close then reload the page
             */
            window.onafterprint = function () {
                location.reload();
            }
        })
    </script>
@endpush
