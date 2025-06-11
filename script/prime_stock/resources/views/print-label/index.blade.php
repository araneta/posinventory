@extends('layouts.app')

@section('title', __('Print Labels'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui/css/jquery-ui.min.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Print Labels') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="warehouse" class="form-label fs-14 text-dark">{{ __('Warehouse') }} <span
                                    class="text-danger">*</span></label>
                            <select class="js-example-basic-single" name="warehouse" id="warehouse">
                                <option selected disabled>{{ __('-- Select Warehouse --') }}</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
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
                                <th scope="col" width="20%">{{ __('Name') }}</th>
                                <th scope="col" width="20%">{{ __('Code') }}</th>
                                <th scope="col" width="10%">{{ __('Price') }}</th>
                                <th scope="col" width="10%">{{ __('Quantity') }}</th>
                            </tr>
                            </thead>
                            <tbody id="tableBody">

                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="paper_size" class="form-label fs-14 text-dark">{{ __('Paper Size') }} <span
                                    class="text-danger">*</span></label>
                            <select class="js-example-basic-single" name="paper_size" id="paper_size">
                                <option selected disabled>{{ __('-- Select Paper Size --') }}</option>
                                <option value="40">{{ __('40 Per Sheet') }} (a4) (1.799 * 1.003)</option>
                                <option value="30">{{ __('30 Per Sheet') }} (2.625 * 1)</option>
                                <option value="24">{{ __('24 Per Sheet') }} (a4) (2.48 * 1.334)</option>
                                <option value="20">{{ __('20 Per Sheet') }} (4 * 1)</option>
                                <option value="18">{{ __('18 Per Sheet') }} (a4) (2.5 * 1.835)</option>
                                <option value="14">{{ __('14 Per Sheet') }} (4 * 1.33)</option>
                                <option value="12">{{ __('12 Per Sheet') }} (a4) (2.5 * 2.834)</option>
                                <option value="10">{{ __('10 Per Sheet') }} (4 * 2)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex">
                        <button type="button" class="btn btn-primary label-btn me-2 rfrsBtn">
                            <i class="ri-refresh-line label-btn-icon me-2"></i>
                            {{ __('Refresh') }}
                        </button>
                        <button type="button" class="btn btn-danger label-btn me-2 resetBtn">
                            <i class="ri-shut-down-line label-btn-icon me-2"></i>
                            {{ __('Reset') }}
                        </button>
                        <button class="btn btn-success label-btn me-2 printBtn">
                            <i class="ri-printer-line label-btn-icon me-2"></i>
                            {{ __('Print Labels') }}
                        </button>
                    </div>
                    <hr>
                    {{--Show Bar Code By Ajax--}}
                    <div class="barcode" id="barcode-sheet">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Popup Modal -->
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

            let currency = '{{ defaultCurrency() }}';
            $("#product").autocomplete({
                source: function (request, response) {
                    let wareHouse = $('#warehouse').val();
                    if(wareHouse === null){
                        notifier.alert("{{ __('Please Select Warehouse First') }}");
                        $('#product').val('');
                        return;
                    }
                    $.ajax({
                        url: "{{ route('purchase.product') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            search: request.term,
                            warehouse: wareHouse
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
                        $('#code').val(ui.item.code);
                        $('#variation_name').val(ui.item.variants[0].name);
                        $('#product_id').val(ui.item.id);
                        $('#stock_quantity').val(ui.item.quantity);
                        $('#variation_id').val(ui.item.variants[0].id);
                        let values = ui.item.variants;
                        let html = '';
                        values.forEach(function (value) {
                            html += `<input type="radio" class="btn-check" name="variation_value_modal" value="${value.pivot.value}|${currency}${value.pivot.sale_price}" id="variation_value_modal_${value.pivot.value}">
                            <label class="btn btn-outline-primary m-1" for="variation_value_modal_${value.pivot.value}">${value.pivot.value} (${currency}${value.pivot.sale_price})</label>`;
                        });
                        $('#variation_value_data').html(html);
                    } else {
                        var html = '<tr class="rowCount">';
                        html += `<td>${ui.item.label}</td>`
                        html += `<input type="hidden" name="product_id" class="form-control" value="${ui.item.id}" readonly>`
                        html += `<td>${ui.item.code}</td>`;
                        html += `<td>${ui.item.price}</td>`
                        html += `<td><div class="input-group">
                                        <input type="number" class="form-control quantity" name="quantity" value="1"
                                            aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">piece</span>
                                    </div></td>`
                        html += '</tr>';
                        $('#tableBody').html(html);
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
                let code = $('#code').val();
                let variation_name = $('#variation_name').val();
                let variation_value = $('input[name="variation_value_modal"]:checked').val();
                if (!variation_value) {
                    notifier.alert("{{ __('Please Select Variation Value') }}");
                    return;
                }
                let split = variation_value.split('|');
                let price = split[1];
                price = parseFloat(price.replace(/[^0-9.-]+/g, ""))
                variation_value = split[0];
                let total = price * quantity;
                let html = '<tr class="rowCount">';
                html += `<td>${product_name} (${variation_value})</td>`
                html += `<input type="hidden" name="product_id" class="form-control" value="${product_id}" readonly>`
                html += `<input type="hidden" name="variation_id" class="form-control" value="${variation_id}" readonly>`
                html += `<input type="hidden" name="variation_value" class="form-control" value="${variation_value}" readonly>`
                html += `<td>${code}</td>`;
                html += `<td>${price}</td>`
                html += `<td><div class="input-group">
                            <input type="number" class="form-control quantity" name="quantity" value="${quantity}"
                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">piece</span>
                        </div></td>`
                html += '</tr>';
                $('#tableBody').html(html);
                $('#productPopupModal').modal('hide');
                $('#product').val('');
            });

            $(document).on('change', '#paper_size', function(){
                let rowCount = $(".rowCount").length;
                if(rowCount === 0){
                    notifier.alert("{{ __('Please Select Product First') }}");
                    return;
                }
                printLabel();
            });

            $(document).on('click', '.rfrsBtn', function(){
                let rowCount = $(".rowCount").length;
                if(rowCount === 0){
                    notifier.alert("{{ __('Please Select Product First') }}");
                    return;
                }
                printLabel();
            });

            function printLabel()
            {
                let paper_size = $('#paper_size').val();
                let quantity = $('.quantity').val();
                let product_id = $('input[name="product_id"]').val();
                $.ajax({
                    url: "{{ route('print-labels.print') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        paper_size: paper_size,
                        quantity: quantity,
                        product_id: product_id
                    },
                    success: function (data) {
                        let barcode_sheet = $('.barcode');
                        barcode_sheet.html(data);
                    }
                });
            }

            $(document).on('click', '.resetBtn', function(){
                console.log("Hello");
                $('#tableBody').html('');
                $('#product').val('');
                $('#paper_size').val('');
                $('.barcode').html('');
            })

            $(document).on('click', '.printBtn', function(){
                let rowCount = $(".rowCount").length;
                if(rowCount === 0){
                    notifier.alert("{{ __('Please Select Product First') }}");
                    return;
                }
                let section = document.getElementById('barcode-sheet');
                let windowObject = window.open('', 'PrintWindow', 'width=750,height=650,top=50,left=50');
                windowObject.document.write(section.innerHTML);
                windowObject.document.close();
                windowObject.focus();
                windowObject.print();
            });
        });
    </script>
@endpush
