@extends('layouts.app')

@section('title', __('Edit Purchase Return'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Edit Purchase  Return') }}</h5>
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
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <form action="{{ route('purchase-return.update', $purchase->id) }}" class="row g-3" method="POST">
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
                                <select class="js-example-basic-single" name="supplier" id="supplier">
                                    <option selected disabled>{{ __('-- Select Supplier --') }}</option>
                                    @foreach($suppliers as $supplier)
                                        <option
                                            value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
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
                                               value="{{ $purchase->return_date }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warehouse" class="form-label fs-14 text-dark">{{ __('Warehouse') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="warehouse" id="warehouse">
                                    <option selected disabled>{{ __('-- Select Warehouse --') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}" {{ $purchase->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="product" class="form-label fs-14 text-dark">{{ __('Products') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="ri-search-line"></i></span>
                                    <input type="text" class="form-control form-control-lg" name="product" id="product"
                                           placeholder="Search Product....">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead class="table-secondary">
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Quantity') }}</th>
                                    <th scope="col">{{ __('Price') }}</th>
                                    <th scope="col">{{ __('Total') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                @foreach($purchase->returns as $product)
                                    <tr>
                                        <td><input type="text" class="form-control" value="{{ $product->product->name }} @if($product->variation_id != null)({{ $product->variation_value }})@endif"
                                                   readonly></td>
                                        <input type="hidden" name="product_id[]" class="form-control"
                                               value="{{ $product->id }}" readonly>
                                        @if($product->variation_id != null)
                                            <input type="hidden" name="variation_id[]" class="form-control"
                                                   value="{{ $product->variation_id }}" readonly>
                                            <input type="hidden" name="variation_value[]" class="form-control"
                                                   value="{{ $product->variation_value }}" readonly>
                                        @endif
                                        <td>
                                            <div class="input-group">
                                                <input type="number" class="form-control quantity" name="quantity[]"
                                                       value="{{ $product->return_quantity }}"
                                                       aria-label="Recipient's username"
                                                       aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">{{ $product->product->unit->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">$</span>
                                                <input type="number" class="form-control product_price" name="price[]"
                                                       value="{{ $product->return_price }}"
                                                       aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon2">$</span>
                                                <input type="number" class="form-control total" name="total[]"
                                                       value="{{ $product->total_return_price }}"
                                                       aria-label="Recipient's username" aria-describedby="basic-addon2"
                                                       readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-icon dltBtn disabled"><i
                                                    class="ri-delete-bin-2-line"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="note" class="form-label fs-14 text-dark">{{ __('Note') }}</label>
                                <textarea name="note" id="note" cols="30" rows="2" class="form-control">{!! $purchase->note !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="return_amount" class="form-label fs-14 text-dark">{{ __('Return Amount') }}
                                    <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="return_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg return_amount"
                                           name="return_amount" value="{{ $purchase->return_amount }}"
                                           aria-label="Currency" id="return_amount" aria-describedby="return_amount_addon"
                                           readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="return_discount" class="form-label fs-14 text-dark">{{ __('Lessed') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="return_discount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg return_discount" name="return_discount"
                                           value="{{ $purchase->return_discount }}"
                                           aria-label="Currency" aria-describedby="return_discount_addon" id="return_discount">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="receivable_amount"
                                       class="form-label fs-14 text-dark">{{ __('Receivable Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="receivable_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg receivable_amount"
                                           name="receivable_amount" value="{{ $purchase->receivable_amount }}"
                                           aria-label="Currency" aria-describedby="receivable_amount_addon"
                                           id="receivable_amount" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="account" class="form-label fs-14 text-dark">{{ __('Account') }} </label>
                                <select class="js-example-basic-single" name="account" id="account">
                                    <option selected disabled>{{ __('-- Select Account --') }}</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $purchase->returnTransaction->account_id == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="received_amount"
                                       class="form-label fs-14 text-dark">{{ __('Received Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="received_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg received_amount"
                                           name="received_amount" value="{{ $purchase->received_amount }}"
                                           aria-label="Currency" aria-describedby="received_amount_addon" id="received_amount"
                                    >
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="due_amount"
                                       class="form-label fs-14 text-dark">{{ __('Due Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="due_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg due_amount"
                                           name="due_amount" value="{{ $purchase->returnDueAmount() }}"
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
            /* To choose date */
            flatpickr("#date");
            let price = {{ $purchase->return_amount }};
            let payableAmount = {{ $purchase->receivable_amount }};
            let discount = {{ $purchase->return_discount }};
            let receivedAmount = {{ $purchase->received_amount }};
            $("#product").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ route('purchase.product') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            search: request.term
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                create: function () {
                    $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
                        return $('<li>')
                            .addClass('list-group-item')
                            .append(`<div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm">
                                                    <img src="{{{ asset('assets/images/') }}}/${item.image}" alt="img">
                                                </span>
                                                <div class="ms-2 fw-semibold">
                                                    ${item.label} (${item.code})
                                                </div>
                                            </div>`)
                            .appendTo(ul);
                    };
                },
                select: function (event, ui) {
                    var html = '<tr>';
                    html += `<td><input type="text" class="form-control" value="${ui.item.label}" readonly></td>`
                    html += `<input type="hidden" name="product_id[]" class="form-control" value="${ui.item.id}" readonly>`
                    html += `<td><div class="input-group">
                                        <input type="number" class="form-control quantity" name="quantity[]" value="1"
                                            aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">${ui.item.unit.name}</span>
                                    </div></td>`;
                    html += `<td><div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                        <input type="number" class="form-control product_price" name="price[]" value="${ui.item.price}"
                                            aria-label="Username" aria-describedby="basic-addon1">
                                    </div></td>`
                    html += `<td><div class="input-group">
                                        <span class="input-group-text" id="basic-addon2">$</span>
                                        <input type="number" class="form-control total" name="total[]" value="${ui.item.price}"
                                            aria-label="Recipient's username" aria-describedby="basic-addon2" readonly>
                                    </div></td>`
                    html += `<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`
                    html += '</tr>';
                    $('#tableBody').append(html);

                    price += parseFloat(ui.item.price);
                    updatePayable();
                    $('#product').val('');
                    return false;
                }
            });

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

            //Receiveable Amount
            $(document).on('keyup', '.received_amount', function () {
                let getReceivedAmount = $(this).val();
                receivedAmount = getReceivedAmount;
                updatePayable();
            });

            //quantity
            $(document).on('input', '.quantity, .product_price', function () {
                var row = $(this).closest('tr');
                let quantity = parseInt(row.find('input[type="number"]').eq(0).val());
                let rowPrice = parseFloat(row.find('input[type="number"]').eq(1).val());
                let totalPrice = parseFloat(row.find('input[type="number"]').eq(2).val());
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
                $("#total_amount").val(price);
                payableAmount = price - discount;
                $("#receivable_amount").val(payableAmount);
                let dueAmount = payableAmount - receivedAmount;
                $("#due_amount").val(dueAmount);
            }
        })
    </script>
@endpush
