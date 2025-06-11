@extends('layouts.app')

@section('title', __('Edit Sale Return'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Edit Sale  Return') }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="{{ route('sale-return.index') }}" class="btn btn-danger label-btn">
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
                    <form action="{{ route('sale-return.update', $sale->id) }}" class="row g-3" method="POST">
                        @csrf
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="invoice_no" class="form-label fs-14 text-dark">{{ __('Invoice No') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                       value="{{ $sale->invoice_no }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="customer" class="form-label fs-14 text-dark">{{ __('Customer') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer" name="customer"
                                       value="{{ $sale->customer->name }}" readonly>
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
                                               value="{{ $sale->return_date }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warehouse" class="form-label fs-14 text-dark">{{ __('Warehouse') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="warehouse" name="warehouse"
                                       value="{{ $sale->warehouse->name }}" readonly>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead class="table-secondary">
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Sale Qty') }}</th>
                                    <th scope="col">{{ __('Return Qty') }}</th>
                                    <th scope="col">{{ __('Price') }}</th>
                                    <th scope="col">{{ __('Total') }}</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                @foreach($sale->returns as $product)
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
                                                <input type="text" class="form-control sale_quantity" name="sale_quantity[]"
                                                       value="{{ $sale->products->where('pivot.sale_id', $product->pivot->sale_id)->first()->pivot->quantity }}"
                                                       aria-label="Recipient's username"
                                                       aria-describedby="basic-addon2" readonly>
                                                <span class="input-group-text" id="basic-addon2">{{ $product->unit->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" class="form-control quantity" name="quantity[]"
                                                       value="{{ $product->pivot->quantity }}"
                                                       aria-label="Recipient's username"
                                                       aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">{{ $product->unit->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">{{ defaultCurrency() }}</span>
                                                <input type="number" class="form-control product_price" name="price[]"
                                                       value="{{ $product->pivot->price }}"
                                                       aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon2">{{ defaultCurrency() }}</span>
                                                <input type="number" class="form-control total" name="total[]"
                                                       value="{{ $product->pivot->total_price }}"
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
                                <label for="note" class="form-label fs-14 text-dark">{{ __('Note') }}</label>
                                <textarea name="note" id="note" cols="30" rows="2" class="form-control">
                                    {!! $sale->note !!}
                                </textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="return_amount" class="form-label fs-14 text-dark">{{ __('Return Amount') }}
                                    <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="return_amount_addon">{{ defaultCurrency() }}</span>
                                    <input type="number" class="form-control form-control-lg return_amount"
                                           name="return_amount" value="{{ $sale->return_amount }}"
                                           aria-label="Currency" id="return_amount" aria-describedby="return_amount_addon"
                                           readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="return_discount" class="form-label fs-14 text-dark">{{ __('Return Discount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="return_discount_addon">{{ defaultCurrency() }}</span>
                                    <input type="text" class="form-control form-control-lg return_discount" name="return_discount"
                                           value="{{ $sale->return_discount }}"
                                           aria-label="Currency" aria-describedby="return_discount_addon" id="return_discount">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="payable_amount"
                                       class="form-label fs-14 text-dark">{{ __('Payable Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="payable_amount_addon">{{ defaultCurrency() }}</span>
                                    <input type="number" class="form-control form-control-lg payable_amount"
                                           name="payable_amount" value="{{ $sale->payable_amount }}"
                                           aria-label="Currency" aria-describedby="payable_amount_addon"
                                           id="payable_amount" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="account" class="form-label fs-14 text-dark">{{ __('Account') }} </label>
                                <select class="js-example-basic-single" name="account" id="account">
                                    <option selected disabled>{{ __('-- Select Account --') }}</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $sale->returnTransaction->account_id == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="paid_amount"
                                       class="form-label fs-14 text-dark">{{ __('Paid Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="paying_amount_addon">{{ defaultCurrency() }}</span>
                                    <input type="text" class="form-control form-control-lg paid_amount"
                                           name="paid_amount" value="{{ $sale->paying_amount }}"
                                           aria-label="Currency" aria-describedby="paying_amount_addon" id="paid_amount"
                                           >
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="due_amount"
                                       class="form-label fs-14 text-dark">{{ __('Due Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="due_amount_addon">{{ defaultCurrency() }}</span>
                                    <input type="number" class="form-control form-control-lg due_amount"
                                           name="due_amount" value="{{ $sale->returnDueAmount() }}"
                                           aria-label="Currency" aria-describedby="due_amount_addon" id="due_amount"
                                           readonly>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
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
            var options = {
                'position': 'top-right'
            }
            var notifier = new AWN(options);
            /* To choose date */
            flatpickr("#date");
            let price = {{ $sale->return_amount }};
            let payableAmount = {{ $sale->payable_amount }};
            let discount = {{ $sale->return_discount }};
            let paidAmount = {{ $sale->paying_amount }};

            //Delete Table Row
            $(document).on('click', '.dltBtn', function () {
                var row = $(this).closest('tr');
                var dltPrice = row.find('input[type="number"]').eq(1).val();
                row.remove();
                price -= dltPrice;
                updatePayable();
            });

            //discount
            $(document).on('keyup', '.return_discount', function () {
                let getDiscount = $(this).val();
                discount = getDiscount;
                updatePayable();
            })

            //Paid Amount
            $(document).on('keyup', '.paid_amount', function () {
                let getPaidAmount = $(this).val();
                paidAmount = getPaidAmount;
                updatePayable();
            });

            //quantity
            $(document).on('input', '.quantity, .product_price', function () {
                var row = $(this).closest('tr');
                let quantity = parseInt(row.find('input[type="number"]').eq(0).val());
                let rowPrice = parseFloat(row.find('input[type="number"]').eq(1).val());
                let totalPrice = parseFloat(row.find('input[type="number"]').eq(2).val());
                let sale_quantity = parseFloat(row.find('.sale_quantity').val());
                if(quantity > sale_quantity){
                    notifier.alert("{{ __('Return quantity is not greater than sale quantity.') }}");
                    row.find('input[type="number"]').eq(0).val(sale_quantity);
                }
                let sumPrice = quantity * rowPrice;
                price -= sumPrice;
                row.find('input[type="number"]').eq(2).val(sumPrice);
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
                $(".return_amount").val(price);
                let totalPrice = price - discount;
                $("#payable_amount").val(totalPrice);
                let dueAmount = totalPrice - paidAmount;
                $("#due_amount").val(dueAmount);
            }
        })
    </script>
@endpush
