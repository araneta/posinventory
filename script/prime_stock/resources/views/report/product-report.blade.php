@extends('layouts.app')

@section('title', __('Product Report'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
@endpush
@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Product Report') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    @include('report.include.__filter_product')
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
                            <th scope="col">{{ __('Code') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Product Type') }}</th>
                            <th scope="col">{{ __('Category') }}</th>
                            <th scope="col">{{ __('Qty Sold') }}</th>
                            <th scope="col">{{ __('Amount Sold') }}</th>
                            <th scope="col">{{ __('Qty Purchased') }}</th>
                            <th scope="col">{{ __('Amount Purchased') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr class="text-center">
                                <td>
                                    <strong>{{ $product->code ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                </td>
                                <td>
                                    {{ ucwords($product->product_type) ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $product->category->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $product->totalSaleQuantity() }} {{ $product->unit->name }}
                                </td>
                                <td>
                                    {{ showAmount($product->totalSalePrice()) }}
                                </td>
                                <td>
                                    {{ $product->totalPurchaseQuantity() }} {{ $product->unit->name }}
                                </td>
                                <td>
                                    {{ showAmount($product->totalPurchasePrice()) }}
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
@endsection
@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        $(function() {
            "use strict"
            $('.js-example-basic-single').select2();
            flatpickr("#date", {
                mode: "range",
                dateFormat: "Y-m-d",
            });
        })
    </script>
@endpush
