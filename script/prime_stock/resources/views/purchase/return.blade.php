@extends('layouts.app')

@section('title', __('Return Purchase'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Return  Purchase') }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="{{ route('purchase.index') }}" class="btn btn-danger label-btn">
                    <i class="ri-arrow-go-back-line label-btn-icon me-2"></i>{{ __('Back') }}
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <form action="{{ route('purchase.return.post', $purchase) }}" class="row g-3" method="POST">
                        @csrf
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="invoice_no" class="form-label fs-14 text-dark">{{ __('Invoice No') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                       value="{{ $purchase->invoice_no }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="supplier" class="form-label fs-14 text-dark">{{ __('Supplier') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="supplier" name="supplier"
                                       value="{{ $purchase->supplier->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="date" class="form-label fs-14 text-dark">{{ __('Date') }} <span
                                        class="text-danger">*</span></label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                        <input type="text" class="form-control" name="date" id="date"
                                               value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warehouse" class="form-label fs-14 text-dark">{{ __('Warehouse') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="warehouse" name="warehouse"
                                       value="{{ $purchase->warehouse->name }}" readonly>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead class="table-secondary">
                                <tr>
                                    <th class="w-20" scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Purchase Qty') }}</th>
                                    <th scope="col">{{ __('Stock Qty') }}</th>
                                    <th scope="col">{{ __('Return Qty') }}</th>
                                    <th scope="col">{{ __('Price') }}</th>
                                    <th scope="col">{{ __('Total') }}</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                @foreach($purchase->products as $product)
                                    <tr>
                                        <td><input type="text" class="form-control" value="{{ $product->name }} @if($product->product_type == 'variation')({{ $product->pivot->variation_value }})@endif"
                                                   readonly></td>
                                        <input type="hidden" name="product_id[]" class="form-control"
                                               value="{{ $product->id }}" readonly>
                                        @if($product->product_type == 'variation')
                                            <input type="hidden" name="variation_id[]" class="form-control"
                                                   value="{{ $product->pivot->variation_id }}" readonly>
                                            <input type="hidden" name="variation_value[]" class="form-control"
                                                   value="{{ $product->pivot->variation_value }}" readonly>
                                        @endif
                                        <td>
                                            <div class="input-group">
                                                <input type="number" class="form-control purchase_quantity" name="purchase_quantity[]"
                                                       value="{{ $product->pivot->quantity }}"
                                                       aria-label="Recipient's username"
                                                       aria-describedby="basic-addon2" readonly>
                                                <span class="input-group-text" id="basic-addon2">{{ $product->unit->name }}</span>
                                            </div>
                                        </td>
                                        @php
                                            if($product->product_type == 'single'){
                                                $productStock = stock_quantity($product->id, $purchase->warehouse_id);
                                            }
                                            if($product->product_type == 'variation'){
                                                $productStock = $product->variants->where('pivot.value', $product->pivot->variation_value)->first()->pivot->quantity;
                                            }
                                        @endphp
                                        <td>
                                            <div class="input-group">
                                                <input type="number" class="form-control product_quantity" name="product_quantity[]"
                                                       value="{{ $productStock }}"
                                                       aria-label="Recipient's username"
                                                       aria-describedby="basic-addon2" readonly>
                                                <span class="input-group-text" id="basic-addon2">{{ $product->unit->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" class="form-control quantity" name="quantity[]"
                                                       aria-label="Recipient's username"
                                                       aria-describedby="basic-addon2" autofocus>
                                                <span class="input-group-text" id="basic-addon2">{{ $product->unit->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">$</span>
                                                <input type="number" class="form-control product_price" name="price[]"
                                                       value="{{ $product->pivot->price }}"
                                                       aria-label="Username" aria-describedby="basic-addon1" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon2">$</span>
                                                <input type="number" class="form-control total" name="total[]"
                                                       value="0"
                                                       aria-label="Recipient's username" aria-describedby="basic-addon2"
                                                       readonly>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="note" class="form-label fs-14 text-dark">{{ __('Return Note') }}</label>
                                <textarea name="note" id="note" cols="30" rows="2" class="form-control">
                                    {!! $purchase->note !!}
                                </textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="total_amount"
                                       class="form-label fs-14 text-dark">{{ __('Total Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="payable_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg total_amount"
                                           name="total_amount" value="0.00"
                                           aria-label="Currency" aria-describedby="total_amount"
                                           id="total_amount" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="discount" class="form-label fs-14 text-dark">{{ __('Lessed') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="discount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg discount" name="discount"
                                           value=""
                                           aria-label="Currency" aria-describedby="discount_addon" id="discount">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="receivable_amount"
                                       class="form-label fs-14 text-dark">{{ __('Receivable Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="payable_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg receivable_amount"
                                           name="receivable_amount" value="0.00"
                                           aria-label="Currency" aria-describedby="receiveable_amount_addon"
                                           id="receivable_amount" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="account" class="form-label fs-14 text-dark">{{ __('Account') }} </label>
                                <select class="js-example-basic-single" name="account" id="account">
                                    <option selected disabled>{{ __('-- Select Account --') }}</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="received_amount"
                                       class="form-label fs-14 text-dark">{{ __('Received Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="payable_amount_addon">$</span>
                                    <input type="text" class="form-control form-control-lg received_amount"
                                           name="received_amount" value="0.00"
                                           aria-label="Currency" aria-describedby="payable_amount_addon"
                                           id="received_amount">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="due_amount"
                                       class="form-label fs-14 text-dark">{{ __('Due Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="payable_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg due_amount"
                                           name="due_amount" value="0.00"
                                           aria-label="Currency" aria-describedby="payable_amount_addon"
                                           id="due_amount" readonly>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Return') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script>
        $(function () {
            "use strict"
            $('.js-example-basic-single').select2();
            /* To choose date */
            flatpickr("#date", {
                defaultDate: "today"
            });
            let price = 0;
            let payableAmount = 0;
            let discount = 0;
            let receivedAmount = 0;

            //discount
            $(document).on('keyup', '.discount', function () {
                let getDiscount = $(this).val();
                discount = getDiscount;
                updatePayable();
            })

            //Receiveable Amount
            $(document).on('keyup', '.received_amount', function () {
                let getReceivedAmount = $(this).val();
                receivedAmount = getReceivedAmount;
                updatePayable();
            });

            //quantity
            $(document).on('input', '.quantity', function () {
                var row = $(this).closest('tr');
                let quantity = parseInt(row.find('input[type="number"]').eq(2).val());
                let rowPrice = parseFloat(row.find('input[type="number"]').eq(3).val());
                let totalPrice = parseFloat(row.find('input[type="number"]').eq(4).val());
                let sumPrice = quantity * rowPrice;
                price -= sumPrice;
                row.find('input[type="number"]').eq(4).val(sumPrice);
                price += sumPrice;
                updatePayable();
            })

            //write payable amount and total amount price
            function updatePayable() {
                var totalSum = 0;
                $('.total').each(function () {
                    let product_price = parseFloat($(this).val());
                    if (!isNaN(price)) {
                        totalSum += product_price;
                    }
                });
                price = totalSum;
                $("#total_amount").val(price);
                payableAmount = price - discount;
                $("#receivable_amount").val(payableAmount);
                let dueAmount = payableAmount - receivedAmount;
                $("#due_amount").val(dueAmount);
            }
        })
    </script>
@endpush
