@extends('layouts.app')

@section('title', __('Product'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <style>
        .variant_table {
            border-collapse: collapse;
        }

        .variant_table th,
        .variant_table td {
            border: 1px dashed #0a0808;
            padding: 5px;
            color: unset;
        }
    </style>
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Product') }}</h5>
        </div>
        @can('create-product')
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="pe-1 mb-xl-0">
                    <a href="{{ route('product.create') }}" class="btn btn-primary label-btn">
                        <i class="ri-add-circle-line label-btn-icon me-2"></i>{{ __('Add New') }}
                    </a>
                </div>
            </div>
        @endcan
    </div>
    @include('product.__filter')
    <!-- Page Header Close -->
    <div class="card custom-card {{ $products->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($products->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">{{ __('Image') }}</th>
                                <th scope="col">{{ __('Name|SKU|Type|Code') }}</th>
                                <th scope="col">{{ __('Price|Sale Price') }}</th>
                                <th scope="col">{{ __('Category|Brand|unit') }}</th>
                                <th scope="col">{{ __('Stock') }}</th>
                                <th scope="col">{{ __('Total Sale|Alert Qty') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="text-center">
                                    <td>
                                        <img alt="avatar" class="img-thumbnail" width="64px"
                                            src="{{ asset('assets/images/' . $product->image) }}">
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong><br><small>{{ $product->sku }}</small>
                                        <br><strong>{{ ucwords($product->product_type) }}</strong>
                                        <br><small>{{ $product->code }}</small>
                                    </td>
                                    <td>

                                        @if ($product->product_type == 'variation')
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#variationModal" data-value="{{ $product->id }}">
                                                <i class="ri-eye-fill" data-bs-toggle="tooltip" data-bs-target="top"
                                                    title="View Variation Product Price With Details"></i>
                                            @else
                                                <strong>{{ showAmount($product->price) }}</strong><br>
                                                <small>{{ showAmount($product->sale_price) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $product->category->name }}<br>
                                        <small class="text-primary">{{ $product->brand->name }}</small><br>
                                        <small class="text-success">{{ ucwords($product->unit->name) }}</small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-value="{{ $product->id }}" data-bs-target="#stockModal">
                                            <i class="ri-eye-fill" data-bs-toggle="tooltip" data-bs-target="top"
                                                title="View Stock Wirehouse Wise"></i>
                                    </td>
                                    <td>
                                        <strong>{{ $product->totalSale() }} items</strong><br>
                                        @if ($product->product_type == 'variation')
                                            <span class="badge bg-info-transparent"><i class="ri-information-fill" data-bs-toggle="tooltip" data-bs-target="top"
                                                title="This is a variation product you can view alert quantity in variation modal"></i></span>
                                        @else
                                            <span class="badge bg-info-transparent">{{ $product->alert_quantity }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->status)
                                            <span
                                                class="badge rounded-pill bg-success-gradient p-2">{{ __('Active') }}</span>
                                        @else
                                            <span
                                                class="badge rounded-pill bg-danger-gradient p-2">{{ __('Deactivate') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 flex-wrap">
                                            @can('edit-product')
                                                <a href="{{ route('product.edit', $product) }}"
                                                    class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit') }}"><i class="ri-edit-line"></i></a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $products->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>

    @include('product.__variant_modal')
    @include('product.__warehouse_modal')
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            "use strict"
            $('.js-example-basic-single').select2();
            $('#variationModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let productId = button.data('value');
                console.log(productId);
                $.ajax({
                    url: "{{ route('product.variant') }}",
                    type: 'GET',
                    data: {
                        productId: productId
                    },
                    success: function(response) {
                        $('#variationModal .modal-body').html(response);
                    }
                })
            });

            // warehouse model
            $('#stockModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let productId = button.data('value');
                console.log(productId);
                $.ajax({
                    url: "{{ route('product.stock') }}",
                    type: 'GET',
                    data: {
                        productId: productId
                    },
                    success: function(response) {
                        $('#stockModal .modal-body').html(response);
                    }
                })
            });
        });
    </script>
@endpush
