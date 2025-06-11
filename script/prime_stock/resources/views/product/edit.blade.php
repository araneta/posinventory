@extends('layouts.app')

@section('title', __('Edit Product'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('Edit Product') }}
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger-light">{{ __('Back') }} <i
                                class="ri-arrow-go-back-line ms-2 d-inline-block align-middle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('product.update', $product) }}" class="row g-3" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label fs-14 text-dark">{{ __('Product Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $product->name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="code" class="form-label fs-14 text-dark">{{ __('Product Code') }} <span
                                        class="text-danger">*</span></label>

                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="code" name="code"
                                        value="{{ $product->code }}" placeholder="generate the barcode">
                                    <button class="input-group-text" type="button" id="generateBarcode"><i
                                            class="ri-barcode-line"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category" class="form-label fs-14 text-dark">{{ __('Category') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="category" id="category">
                                    <option selected disabled>{{ __('-- Select Category --') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sku" class="form-label fs-14 text-dark">{{ __('SKU') }}
                                    <span class="text-danger">*</span>
                                    <i class="ri-information-fill fs-14" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ __('Stock Keeping Unit (SKU) is a store product and service identification code') }}"></i>
                                </label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="sku" name="sku"
                                        placeholder="Sku" value="{{ $product->sku }}">
                                    <button class="input-group-text" type="button" id="generateSku"><i
                                            class="ri-barcode-line"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="brand" class="form-label fs-14 text-dark">{{ __('Brand') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="brand" id="brand">
                                    <option selected disabled>{{ __('-- Select Brand --') }}</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="unit" class="form-label fs-14 text-dark">{{ __('Unit') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="unit" id="unit">
                                    <option selected disabled>{{ __('-- Select Unit --') }}</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="mb-3">
                                <label for="image" class="form-label">{{ __('Product Image') }}</label>
                                <input class="form-control" type="file" id="image" name="image">
                            </div>
                            @if ($product->image)
                                <div class="mt-3 ms-3">
                                    <button type="button" class="btn btn-primary" onclick="window.open('{{ asset('assets/images/' . $product->image) }}', '_blank')">
                                        <i class="ri-eye-fill"></i>
                                    </button>
                                </div>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="note" class="form-label fs-14 text-dark">{{ __('Note') }}</label>
                                <textarea name="note" id="note" cols="30" rows="2" class="form-control">{{ $product->note }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="form-password"
                                    class="form-label fs-14 text-dark me-2">{{ __('Status') }}</label>
                                <input type="radio" class="btn-check" value="1" name="status"
                                    id="success-outlined" @checked(old('status', $product->status)) />
                                <label class="btn btn-outline-success m-1"
                                    for="success-outlined">{{ __('Active') }}</label>
                                <input type="radio" class="btn-check" value="0" name="status"
                                    id="danger-outlined" @checked(old('status', !$product->status)) />
                                <label class="btn btn-outline-danger m-1"
                                    for="danger-outlined">{{ __('Deactive') }}</label>
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="type" class="form-label fs-14 text-dark">{{ __('Product Type') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="type" id="type">
                                    <option selected disabled>{{ __('-- Select Type --') }}</option>
                                    <option value="single" {{ $product->product_type == 'single' ? 'selected' : '' }}>
                                        {{ __('Single') }}</option>
                                    <option value="variation"
                                        {{ $product->product_type == 'variation' ? 'selected' : '' }}>
                                        {{ __('Variation') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 {{ $product->product_type == 'variation' ? '' : 'd-none' }}"
                            id="variation_sec">
                            <div class="mb-3">
                                <label for="variation" class="form-label fs-14 text-dark">{{ __('Variations') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="variation" id="variation">
                                    <option selected disabled>{{ __('-- Select Variation --') }}</option>
                                    @foreach ($variants as $variation)
                                        <option value="{{ $variation->id }}|{{ arrayToString($variation->values) }}"
                                            {{ $product->product_type == 'variation' && $product->variants->first()->pivot->variation_id == $variation->id ? 'selected' : '' }}>
                                            {{ $variation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 {{ $product->product_type == 'variation' ? '' : 'd-none' }}"
                            id="variation_value">

                        </div>
                        <hr>
                        <div class="row auto_append_sec">
                            @if ($product->product_type == 'single')
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="price" class="form-label fs-14 text-dark">{{ __('Price') }}
                                            <span class="text-danger">*</span></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">{{ defaultCurrency() }}</span>
                                            <input type="text" class="form-control" id="price" name="price[]"
                                                aria-label="Amount (to the nearest dollar)"
                                                value="{{ $product->price }}">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="sale_price" class="form-label fs-14 text-dark">{{ __('Sale Price') }}
                                            <span class="text-danger">*</span></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">{{ defaultCurrency() }}</span>
                                            <input type="text" class="form-control" id="sale_price"
                                                name="sale_price[]" aria-label="Amount (to the nearest dollar)"
                                                value="{{ $product->sale_price }}">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="alert_quantity"
                                            class="form-label fs-14 text-dark">{{ __('Alert Quantity') }}
                                            <span class="text-danger">*</span>
                                            <i class="ri-information-fill fs-14" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="{{ __('Alert for a product shortage') }}"></i>
                                        </label>
                                        <input type="text" class="form-control" id="alert_quantity"
                                            name="alert_quantity[]" value="{{ $product->alert_quantity }}">
                                    </div>
                                </div>
                            @endif
                            @if ($product->product_type == 'variation')
                                @foreach ($product->variants as $v)
                                    <input type="hidden" name="variation_value[]" value="{{ $v->pivot->value }}">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price" class="form-label fs-14 text-dark">{{ __('Price') }}
                                                of {{ $v->pivot->value }} variant
                                                <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">{{ defaultCurrency() }}</span>
                                                <input type="text" class="form-control" id="price" name="price[]"
                                                    aria-label="Amount (to the nearest dollar)"
                                                    value="{{ $v->pivot->price }}">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="sale_price"
                                                class="form-label fs-14 text-dark">{{ __('Sale Price') }}
                                                of {{ $v->pivot->value }} variant
                                                <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">{{ defaultCurrency() }}</span>
                                                <input type="text" class="form-control" id="sale_price"
                                                    name="sale_price[]" aria-label="Amount (to the nearest dollar)"
                                                    value="{{ $v->pivot->sale_price }}">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="alert_quantity"
                                                class="form-label fs-14 text-dark">{{ __('Alert Quantity') }}
                                                of {{ $v->pivot->value }} variant
                                                <span class="text-danger">*</span>
                                                <i class="ri-information-fill fs-14" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="{{ __('Alert for a product shortage') }}"></i>
                                            </label>
                                            <input type="text" class="form-control" id="alert_quantity"
                                                name="alert_quantity[]" value="{{ $v->pivot->alert_quantity }}">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Update Product') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script>
        $(function() {
            "use strict"
            $('.js-example-basic-single').select2();

            //generate barcode
            $('#generateBarcode').click(function() {
                let code = Math.floor(10000000 + Math.random() * 90000000);
                $('#code').val(code);
            });

            @if ($product->product_type == 'variation')
                let selectedValues = [];
                @foreach ($product->variants as $variant)
                    selectedValues.push('{{ $variant->pivot->value }}');
                @endforeach
                let variation = $('#variation').val();
                let variationArr = variation.split('|');
                let variation_id = variationArr[0];
                let values = variationArr[1].split(',');
                let html = '';
                //selected field with variation value
                html += '<div class="mb-3">';
                html += '<input type="hidden" name="variation_id" value="' + variation_id + '">';
                html +=
                    '<label for="variation" class="form-label fs-14 text-dark">Variation Value <span class="text-danger">*</span></label>';
                html +=
                    '<select class="js-example-basic-single" name="variation_value" id="variation_value" multiple>';
                values.forEach(function(value) {
                    // Check if the value exists in the selectedValues array
                    let selected = selectedValues.includes(value) ? 'selected' : '';
                    html += '<option value="' + value + '" ' + selected + '>' + value + '</option>';
                });
                html += '</select>';
                html += '</div>';
                $('#variation_value').html(html);
                $('.js-example-basic-single').select2({
                    placeholder: "Select Variation Value",
                    allowClear: true
                });
            @endif


            function append_html(values = null) {
                return `
                <input type="hidden" name="variation_value[]" value="${values}">
                <div class="col-md-4 ${values}" id="price_sec">
                            <div class="mb-3">
                                <label for="sku" class="form-label fs-14 text-dark">{{ __('Price') }}  ${values !== null ? `of ${values} variant` : ''}
                <span class="text-danger">*</span>
            </label>
            <div class="input-group mb-3">
                <span class="input-group-text">{{ defaultCurrency() }}</span>
                                    <input type="text" class="form-control" id="price" name="price[]"
                                           aria-label="Amount (to the nearest dollar)" placeholder="Price ">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 ${values}" id="sale_price_sec">
                            <div class="mb-3">
                                <label for="sku" class="form-label fs-14 text-dark">{{ __('Sale Price') }} ${values !== null ? `of ${values} variant` : ''}
                <span class="text-danger">*</span>
            </label>
            <div class="input-group mb-3">
                <span class="input-group-text">{{ defaultCurrency() }}</span>
                                    <input type="text" class="form-control" id="sale_price" name="sale_price[]"
                                           aria-label="Amount (to the nearest dollar)" placeholder="Sale Price ">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 ${values}" id="alert_quantity_sec">
                            <div class="mb-3">
                                <label for="alert_quantity"
                                       class="form-label fs-14 text-dark">{{ __('Alert Quantity') }} ${values !== null ? `of ${values} variant` : ''}
                <span class="text-danger">*</span>
                <i class="ri-information-fill fs-14" data-bs-toggle="tooltip"
                   data-bs-placement="top" title="{{ __('Alert for a product shortage') }}"></i>
                                </label>
                                <input type="text" class="form-control" id="alert_quantity" name="alert_quantity[]"
                                       placeholder="">
                            </div>
                        </div>`;
            }


            // Product Type Change
            $('#type').change(function() {
                let type = $(this).val();
                console.log(type)
                if (type === 'single') {
                    $('.auto_append_sec').html(append_html());
                    $('#variation_sec').addClass('d-none');
                    $('#variation_value').addClass('d-none');
                }
                if (type === 'variation') {
                    $('#variation_sec').removeClass('d-none');
                    $('#variation_value').removeClass('d-none');
                    $('.auto_append_sec').html('');
                }
            });

            //variation select
            $('#variation').change(function() {
                let variation = $(this).val();
                let variationArr = variation.split('|');
                let variation_id = variationArr[0];
                let values = variationArr[1].split(',');
                let html = '';
                //selected field with variation value
                html += '<div class="mb-3">';
                html += '<input type="hidden" name="variation_id" value="' + variation_id + '">';
                html +=
                    '<label for="variation" class="form-label fs-14 text-dark">Variation Value <span class="text-danger">*</span></label>';
                html +=
                    '<select class="js-example-basic-single" name="variation_value" id="variation_value" multiple>';
                values.forEach(function(value) {
                    html += '<option value="' + value + '">' + value + '</option>';
                });
                html += '</select>';
                html += '</div>';
                $('#variation_value').html(html);
                $('.js-example-basic-single').select2({
                    placeholder: "Select Variation Value",
                    allowClear: true
                });
            });

            //Variation Value Change
            $('#variation_value').on('select2:select', function(e) {
                let variation_value = e.params.data.text;
                $('.auto_append_sec').append(append_html(variation_value));
            });

            //Variation Value Change
            $('#variation_value').on('select2:unselect', function(e) {
                let variation_value = e.params.data.text;
                let auto_append_sec = $('.auto_append_sec');
                auto_append_sec.find(`input[value="${variation_value}"]`).remove();
                auto_append_sec.find(`.${variation_value}`).remove();
            });

            //generate sku
            $('#generateSku').click(function() {
                let product_name = $('#name').val();
                let product_code = $('#code').val();
                let skuFormat = 'S';
                if (product_name) {
                    let words = product_name.split(' ');
                    words.forEach(word => {
                        skuFormat += word[0];
                    });
                } else {
                    skuFormat += 'DP-';
                }
                if (product_code) {
                    skuFormat += '-' + product_code.substring(product_code.length - 3);
                } else {
                    skuFormat += Math.floor(10000 + Math.random() * 90000);
                }
                $('#sku').val(skuFormat);
            });
        })
    </script>
@endpush
