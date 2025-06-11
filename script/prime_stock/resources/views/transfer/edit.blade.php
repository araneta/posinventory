@extends('layouts.app')

@section('title', __('Edit Transfer'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Edit Transfer') }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="{{ route('transfer.index') }}" class="btn btn-danger label-btn">
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
                    <form action="{{ route('transfer.update', $transfer->id) }}" class="row g-3" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="date" class="form-label fs-14 text-dark">{{ __('Date') }} <span
                                        class="text-danger">*</span></label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                        <input type="text" class="form-control" name="date" id="date"
                                               value="{{ $transfer->date }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warehouse" class="form-label fs-14 text-dark">{{ __('From Warehouse') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="from_warehouse" id="from_warehouse">
                                    <option selected disabled>{{ __('-- Select Warehouse --') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ $transfer->from_warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warehouse" class="form-label fs-14 text-dark">{{ __('To Warehouse') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="to_warehouse" id="to_warehouse">
                                    <option selected disabled>{{ __('-- Select Warehouse --') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ $transfer->to_warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
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
                                    <th scope="col">{{ __('Current Stock') }}</th>
                                    <th scope="col">{{ __('Transfer Qty') }}
                                        <span class="text-danger">*</span>
                                    </th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                @foreach($transfer->products as $transferProduct)
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" value="{{ $transferProduct->product->name }}@if($transferProduct->product->product_type != 'single')({{ $transferProduct->variation_value}})@endif" readonly>
                                            <input type="hidden" name="product_id[]" class="form-control" value="{{ $transferProduct->product_id }}" readonly>
                                            <input type="hidden" name="stock_quantity[]" class="form-control stock_quantity" value="{{ stock_quantity($transferProduct->product_id, $transfer->from_warehouse_id)+$transferProduct->quantity }}" readonly>
                                            <input type="hidden" name="variation_id[]" class="form-control" value="{{ $transferProduct->variation_id }}" readonly>
                                            <input type="hidden" name="variation_value[]" class="form-control" value="{{ $transferProduct->variation_value }}" readonly>
                                        </td>
                                        <td>
                                            @if($transferProduct->product->product_type == 'single')
                                                {{ stock_quantity($transferProduct->product_id, $transfer->from_warehouse_id) + $transferProduct->quantity }}
                                            @else
                                                {{ variant_stock_quantity($transferProduct->product_id, $transfer->from_warehouse_id,$transferProduct->variation_id,$transferProduct->variation_value)+ $transferProduct->quantity }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" class="form-control qty" name="qty[]" value="{{ $transferProduct->quantity }}"
                                                       aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">{{ $transferProduct->product->unit->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button>
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
                                    {{ $transfer->note }}
                                </textarea>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('purchase.include.__product_popup')
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(function () {
            "use strict"
            $('.js-example-basic-single').select2();
            /* To choose date */
            flatpickr("#date", {
                defaultDate: "today"
            });
            var options = {
                'position': 'top-right'
            }
            var notifier = new AWN(options);
            let currency = '{{ defaultCurrency() }}';
            $("#product").autocomplete({
                source: function (request, response) {
                    let wirehouse = $('#from_warehouse').val();
                    if(wirehouse == null){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: '{{ __('Please Select From Warehouse!') }}',
                        })
                        return false;
                    }
                    $.ajax({
                        url: "{{ route('purchase.product') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            search: request.term,
                            warehouse: wirehouse,
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
                            html += `<input type="radio" class="btn-check variation_value_modal" name="variation_value_modal" value="${value.pivot.value}|${currency}${value.pivot.sale_price}|${value.pivot.current_stock}|${ui.item.unit.name}" id="variation_value_modal_${value.pivot.value}">
                            <label class="btn btn-outline-primary m-1" for="variation_value_modal_${value.pivot.value}">${value.pivot.value} (${currency}${value.pivot.sale_price})</label>`;
                        });
                        $('#variation_value_data').html(html);
                    } else {
                        var html = '<tr>';
                        html+=`<td><input type="text" class="form-control" value="${ui.item.label}" readonly></td>`
                        html+=`<input type="hidden" name="product_id[]" class="form-control" value="${ui.item.id}" readonly>`
                        html += `<input type="hidden" name="variation_id[]" class="form-control" value="" readonly>`
                        html += `<input type="hidden" name="variation_value[]" class="form-control" value="" readonly>`
                        html+=`<input type="hidden" name="stock_quantity[]" class="form-control stock_quantity" value="${ui.item.quantity_in_wirehouse}" readonly>`
                        html+=`<td>${ui.item.quantity_in_wirehouse} ${ui.item.unit.name}</td>`;
                        html+=`<td><div class="input-group">
                                            <input type="number" class="form-control qty" name="qty[]" value="1"
                                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <span class="input-group-text" id="basic-addon2">${ui.item.unit.name}</span>
                                        </div></td>`
                        html+=`<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`
                        html += '</tr>';
                        $('#tableBody').append(html);
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
                let warehouse_quantity = split[2];
                let unit = split[3];
                price = parseFloat(price.replace(/[^0-9.-]+/g,""))
                variation_value = split[0];
                let total = price * quantity;
                let total_quantity = parseInt(warehouse_quantity) + parseInt(quantity);
                let html = '<tr>';
                html += `<td><input type="text" class="form-control" value="${product_name} (${variation_value})" readonly></td>`
                html += `<input type="hidden" name="product_id[]" class="form-control" value="${product_id}" readonly>`
                html += `<input type="hidden" name="variation_id[]" class="form-control" value="${variation_id}" readonly>`
                html += `<input type="hidden" name="variation_value[]" class="form-control" value="${variation_value}" readonly>`
                html+=`<input type="hidden" name="stock_quantity[]" class="form-control stock_quantity" value="${warehouse_quantity}" readonly>`
                html+=`<td>${warehouse_quantity} ${unit}</td>`;
                html+=`<td><div class="input-group">
                                    <input type="number" class="form-control qty" name="qty[]" value="${quantity}"
                                        aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2">${unit}</span>
                                </div></td>`
                html+=`<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`
                html += '</tr>';
                $('#tableBody').append(html);
                $('#productPopupModal').modal('hide');
                $('#product').val('');
            });

            $(document).on('keyup', '.qty', function(){
                let stock = $(this).closest('tr').find('.stock_quantity').val();
                console.log(stock);
                let qty = $(this).val();
                if(parseInt(qty) > parseInt(stock)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: '{{ __('Transfer Quantity is greater than stock quantity!') }}',
                    })
                    $(this).val(1);
                }
            })
            //Delete Table Row
            $(document).on('click', '.dltBtn', function() {
                let row = $(this).closest('tr');
                let dltPrice = row.find('input[type="number"]').eq(1).val();
                row.remove();
            });

            //Select Variation Value
            $(document).on('change', '.variation_value_modal', function () {
                let split = $(this).val().split('|');
                let quantity = split[2];
                $('#current_stock').val(quantity);
            });
        })
    </script>
@endpush
