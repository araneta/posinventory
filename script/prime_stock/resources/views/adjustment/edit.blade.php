@extends('layouts.app')

@section('title', __('Edit Adjustment'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Edit Adjustment') }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="{{ route('adjustment.index') }}" class="btn btn-danger label-btn">
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
                    <form action="{{ route('adjustment.update', $adjustment->id) }}" class="row g-3" method="POST">
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
                                               value="{{ $adjustment->date }}">
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
                                        <option value="{{ $warehouse->id }}" {{ $adjustment->warehouse_id == $warehouse->id || settings('default_warehouse') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
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
                                    <th scope="col">{{ __('Stock - After Adjust') }}</th>
                                    <th scope="col">{{ __('Adjust Qty') }}</th>
                                    <th scope="col">{{ __('Type') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach($adjustment->products as $adjustmentProduct)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" value="{{ $adjustmentProduct->product->name }} ({{ $adjustmentProduct->variation_value }})" readonly>
                                                <input type="hidden" name="product_id[]" class="form-control" value="{{ $adjustmentProduct->product_id }}" readonly>
                                                <input type="hidden" name="variation_id[]" class="form-control" value="{{ $adjustmentProduct->variation_id }}" readonly>
                                                <input type="hidden" name="variation_value[]" class="form-control" value="{{ $adjustmentProduct->variation_value }}" readonly>
                                            </td>
                                            <td>
                                                @if($adjustmentProduct->product->product_type == 'single')
                                                {{ stock_quantity($adjustmentProduct->product_id, $adjustment->warehouse_id)-$adjustmentProduct->quantity }}
                                                @else
                                                {{ variant_stock_quantity($adjustmentProduct->product_id, $adjustment->warehouse_id,$adjustmentProduct->variation_id,$adjustmentProduct->variation_value)-$adjustmentProduct->quantity }}
                                                @endif
                                            </td>
                                            <td><span class="stock_after_adjust">
                                                @if($adjustmentProduct->product->product_type == 'single')
                                                {{ stock_quantity($adjustmentProduct->product_id, $adjustment->warehouse_id) }}
                                                @else
                                                {{ variant_stock_quantity($adjustmentProduct->product_id, $adjustment->warehouse_id,$adjustmentProduct->variation_id,$adjustmentProduct->variation_value) }}
                                                @endif
                                            </span></td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" class="form-control qty" name="qty[]" value="{{ $adjustmentProduct->quantity }}"
                                                        aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                    <span class="input-group-text" id="basic-addon2">{{ $adjustmentProduct->product->unit->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <select name="type[]" class="form-select type">
                                                    <option value="add" {{ $adjustmentProduct->type == 'add' ? 'selected' : '' }}>{{ __('Add') }}(+)</option>
                                                    <option value="subtract" {{ $adjustmentProduct->type == 'subtract' ? 'selected' : '' }}>{{ __('Subtract') }}(-)</option>
                                                </select>
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
                                    {{ $adjustment->note }}
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
                        html+=`<td>${ui.item.quantity_in_wirehouse}${ui.item.unit.name}</td>`;
                        html+=`<td><span class="stock_after_adjust">${ui.item.quantity}${ui.item.unit.name}</span></td>`
                        html+=`<td><div class="input-group">
                                            <input type="number" class="form-control qty" name="qty[]" value="1"
                                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <span class="input-group-text" id="basic-addon2">${ui.item.unit.name}</span>
                                        </div></td>`
                        html+=`<td><select name="type[]" class="form-select type"><option value="add">Add(+)</option><option value="subtract">Subtract(-)</option></select></td>`
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
                html+=`<td>${warehouse_quantity}${unit}</td>`;
                html+=`<td><span class="stock_after_adjust">${total_quantity}${unit}</span></td>`
                html+=`<td><div class="input-group">
                                    <input type="number" class="form-control qty" name="qty[]" value="${quantity}"
                                        aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2">${unit}</span>
                                </div></td>`
                html+=`<td><select name="type[]" class="form-select type"><option value="add">Add(+)</option><option value="subtract">Subtract(-)</option></select></td>`
                html+=`<td><button class="btn btn-danger btn-icon dltBtn"><i class="ri-delete-bin-2-line"></i></button></td>`
                html += '</tr>';
                $('#tableBody').append(html);
                $('#productPopupModal').modal('hide');
                $('#product').val('');
            });

            //on change type and quantity
            $(document).on('keyup', '.qty', function () {
                updateAdjust($(this));
            });

            $(document).on('change', '.type', function () {
                updateAdjust($(this));
            });

            function updateAdjust(thisRow)
            {
                let row = thisRow.closest('tr');
                let stock = row.find('td').eq(1).text();
                let qty = row.find('.qty').val();
                let type = row.find('.type').val();
                let stockAfterAdjust = row.find('.stock_after_adjust');
                if (type === 'add') {
                    stockAfterAdjust.text(parseInt(stock) + parseInt(qty));
                } else {
                    if(qty > stock){
                        notifier.alert("{{ __('You cannot substract more than stock') }}");
                        row.find('.qty').val(1);
                        stockAfterAdjust.text(parseInt(stock) - parseInt(1));
                    }else{
                        stockAfterAdjust.text(parseInt(stock) - parseInt(qty));
                    }
                }
            }

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
