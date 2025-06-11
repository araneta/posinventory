@extends('layouts.app')

@section('title', __('Add Purchase'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Add Purchase') }}</h5>
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
                    <form action="{{ route('purchase.store') }}" class="row g-3" method="POST">
                        @csrf
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="invoice_no" class="form-label fs-14 text-dark">{{ __('Invoice No') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                       value="{{ $invoice_id }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="supplier" class="form-label fs-14 text-dark">{{ __('Supplier') }} <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-2">
                                <select class="js-example-basic-single" name="supplier" id="supplier">
                                    <option selected disabled>{{ __('-- Select Supplier --') }}</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-icon btn-primary-transparent btn-wave" data-bs-toggle="modal"
                                        data-bs-target="#addSupplierModal">
                                    <i class="ri-add-circle-line"></i>
                                </button>
                                </div>
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
                                               placeholder="Choose date">
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
                                        <option value="{{ $warehouse->id }}" {{ settings('default_warehouse') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
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

                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="note" class="form-label fs-14 text-dark">{{ __('Note') }}</label>
                                <textarea name="note" id="note" cols="30" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="total_price" class="form-label fs-14 text-dark">{{ __('Total Price') }}
                                    <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="total_price_addon">$</span>
                                    <input type="number" class="form-control form-control-lg total_price"
                                           name="total_price" value="0.00"
                                           aria-label="Currency" id="total_price" aria-describedby="total_price_addon"
                                           readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="discount" class="form-label fs-14 text-dark">{{ __('Tax') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="discount_addon">%</span>
                                    <input type="text" class="form-control form-control-lg tax" name="tax"
                                           value="0"
                                           aria-label="Currency" aria-describedby="discount_addon" id="tax">
                                </div>
                                <p class="text-muted">Tax Amount: <span class="tax_amount">0</span></p>
                            </div>
                            <div class="mb-3">
                                <label for="discount" class="form-label fs-14 text-dark">{{ __('Discount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="discount_addon">$</span>
                                    <input type="text" class="form-control form-control-lg discount" name="discount"
                                           value="0.00"
                                           aria-label="Currency" aria-describedby="discount_addon" id="discount">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="payable_amount"
                                       class="form-label fs-14 text-dark">{{ __('Payable Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="payable_amount_addon">$</span>
                                    <input type="number" class="form-control form-control-lg payable_amount"
                                           name="payable_amount" value="0.00"
                                           aria-label="Currency" aria-describedby="payable_amount_addon"
                                           id="payable_amount" readonly>
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
                                <label for="paid_amount"
                                       class="form-label fs-14 text-dark">{{ __('Paid Amount') }} </label>
                                <div class="input-group">
                                    <span class="input-group-text" id="payable_amount_addon">$</span>
                                    <input type="text" class="form-control form-control-lg paid_amount"
                                           name="paid_amount" value="0.00"
                                           aria-label="Currency" aria-describedby="payable_amount_addon"
                                           id="paid_amount">
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
                        <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Popup Modal -->
    @include('purchase.include.__product_popup')
    <!-- Add Supplier Modal -->
    @include('purchase.include.__supplier_modal')
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script>
        $(function () {
            "use strict"
            var options = {
                'position': 'top-right'
            }
            var notifier = new AWN(options);
            $('.js-example-basic-single').select2();
            /* To choose date */
            flatpickr("#date", {
                defaultDate: "today"
            });
            let currency = '{{ defaultCurrency() }}';
            let price = 0;
            let payableAmount = 0;
            let tax = 0;
            let discount = 0;
            let paidAmount = 0;
            $("#product").autocomplete({
                source: function (request, response) {
                    let wareHouseId = $('#warehouse').val();
                    if(!wareHouseId){
                        notifier.alert("{{ __('Please Select Warehouse') }}");
                        $('#product').val('');
                        return;
                    }
                    $.ajax({
                        url: "{{ route('purchase.product') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            search: request.term,
                            warehouse: wareHouseId
                        },
                        success: function (data) {
                            console.log(data);
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
                    if (ui.item.product_type == 'variation') {
                        $('#productPopupModal').modal('show');
                        $('#product_name').val(ui.item.label);
                        $('#variation_name').val(ui.item.variants[0].name);
                        $('#product_id').val(ui.item.id);
                        $('#stock_quantity').val(ui.item.quantity);
                        $('#variation_id').val(ui.item.variants[0].id);
                        $('#current_stock').val(0);
                        let values = ui.item.variants;
                        let html = '';
                        values.forEach(function (value) {
                            html += `<input type="radio" class="btn-check variation_value_modal" name="variation_value_modal" value="${value.pivot.value}|${currency}${value.pivot.sale_price}|${ui.item.unit.name}|${value.pivot.current_stock}" id="variation_value_modal_${value.pivot.value}">
                            <label class="btn btn-outline-primary m-1" for="variation_value_modal_${value.pivot.value}">${value.pivot.value} (${currency}${value.pivot.sale_price})</label>`;
                        });
                        $('#variation_value_data').html(html);
                    } else {
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
                }
            });

            // Insert Model Data
            $(document).on('click', '#insert_modal_data', function () {
                let product_id = $('input[name="product_id"]').val();
                let variation_id = $('input[name="variation_id"]').val();
                let quantity = $('input[name="quantity"]').val();
                let product_name = $('#product_name').val();
                let variation_name = $('#variation_name').val();
                let variation_value = $('input[name="variation_value_modal"]:checked').val();
                if(!variation_value){
                    notifier.alert("{{ __('Please Select Variation Value') }}");
                    return;
                }
                let split = variation_value.split('|');
                let price = split[1];
                let unit = split[2];
                price = parseFloat(price.replace(/[^0-9.-]+/g,""))
                variation_value = split[0];
                let total = price * quantity;
                let html = '<tr>';
                html += `<td><input type="text" class="form-control" value="${product_name} (${variation_value})" readonly></td>`
                html += `<input type="hidden" name="product_id[]" class="form-control" value="${product_id}" readonly>`
                html += `<input type="hidden" name="variation_id[]" class="form-control" value="${variation_id}" readonly>`
                html += `<input type="hidden" name="variation_value[]" class="form-control" value="${variation_value}" readonly>`
                html += `<td><div class="input-group">
                            <input type="number" class="form-control quantity" name="quantity[]" value="${quantity}"
                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">${unit}</span>
                        </div></td>`;
                html += `<td><div class="input-group">
                            <span class="input-group-text" id="basic-addon1">$</span>
                            <input type="number" class="form-control product_price" name="price[]" value="${price}"
                                aria-label="Username" aria-describedby="basic-addon1">
                        </div></td>`
                html += `<td><div class="input-group">
                            <span class="input-group-text" id="basic-addon2">$</span>
                            <input type="number" class="form-control total" name="total[]" value="${total}"
                                aria-label="Recipient's username" aria-describedby="basic-addon2" readonly>
                        </div></td>`
                html += `<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`
                html += '</tr>';
                $('#tableBody').append(html);
                $('#productPopupModal').modal('hide');
                updatePayable();
                $('#product').val('');
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
            $(document).on('keyup', '.discount', function () {
                let getDiscount = $(this).val();
                discount = getDiscount;
                updatePayable();
            })

            //Tax
            $(document).on('keyup', '.tax', function () {
                let getTax = $(this).val();
                let taxAmount = (price * getTax) / 100;
                $(".tax_amount").text(taxAmount);
                tax = taxAmount;
                updatePayable();
            })

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
            });

            //Paid Amount
            $(document).on('keyup', '.paid_amount', function () {
                let getPaidAmount = $(this).val();
                paidAmount = getPaidAmount;
                updatePayable();
            });

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
                $("#total_price").val(price);
                let totalPrice = (price + tax) - discount;
                $("#payable_amount").val(totalPrice);
                let dueAmount = totalPrice - paidAmount;
                $("#due_amount").val(dueAmount);
            }

            //Add Supplier
            $(document).on('click', '#add_supplier', function () {
                let name = $('#name').val();
                let email = $('#email').val();
                let phone = $('#phone').val();
                let company = $('#company').val();
                let address = $('#address').val();
                $.ajax({
                    url: "{{ route('supplier.store') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        name: name,
                        email: email,
                        phone: phone,
                        company: company,
                        address: address,
                    },
                    success: function (data) {
                        $('#addSupplierModal').modal('hide');
                        $('#supplier').append(`<option value="${data.id}">${data.name}</option>`);
                        $('#supplier').val(data.id).trigger('change');
                    }
                });
            });

            // variation_value_modal click
            $(document).on('click', '.variation_value_modal', function () {
                let split = $(this).val().split('|');
                let quantity = split[3];
                $('#current_stock').val(quantity);
            });

        })
    </script>
@endpush
