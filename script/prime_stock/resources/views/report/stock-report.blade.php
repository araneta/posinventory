@extends('layouts.app')

@section('title', __('Stock Report'))
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
            <h5 class="page-title fs-21 mb-1">{{ __('Product Stock Report') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    <div class="card custom-card mb-2">
        <div class="card-body">
            <form action="{{ route('report.stock') }}" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="warehouse">{{ __('Select WareHouse') }}</label>
                            <select name="warehouse" id="warehouse" class="form-control">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ request('warehouse') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mt-3">
                        <button class="btn btn-primary label-btn mt-1">
                            <i class="ri-filter-2-fill label-btn-icon me-2"></i>
                            {{ __('Filter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card custom-card {{ $products == null ? 'text-center' : ($products->count() <= 0 ? 'text-center' : '') }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if (!empty($products) && $products->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('Code') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Warehouse') }}</th>
                            <th scope="col">{{ __('Current Stock') }}</th>
                            <th scope="col">{{ __('Total Amount Stock') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <strong>{{ $product->code ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                </td>
                                <td>
                                    {{ warehouseName(request('warehouse')) ?? 'N/A' }}
                                </td>
                                <td>
                                    @if ($product->product_type == 'variation')
                                        <table class="variant_table">
                                            <tr>
                                                <th>{{ __('Variant Name') }}</th>
                                                <th>{{ __('Variant Value') }}</th>
                                                <th>{{ __('Stock') }}</th>
                                            </tr>
                                            @foreach ($product->variants as $variant)
                                            <tr>
                                                <td>{{ $variant->name }}</td>
                                                <td>{{ $variant->pivot->value }}</td>
                                                <td>{{ variant_stock_quantity($product->id, request('warehouse'), $variant->pivot->variation_id, $variant->pivot->value) }}{{ $product->unit->name }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    @else
                                        {{ stock_quantity($product->id, request('warehouse')) }}{{ $product->unit->name }}
                                    @endif
                                </td>
                                <td>
                                @if ($product->product_type == 'variation')
                                        <table class="variant_table">
                                            <tr>
                                                <th>{{ __('Variant Name') }}</th>
                                                <th>{{ __('Variant Value') }}</th>
                                                <th>{{ __('Total Stock Amount') }}</th>
                                            </tr>
                                            @foreach ($product->variants as $variant)
                                            <tr>
                                                <td>{{ $variant->name }}</td>
                                                <td>{{ $variant->pivot->value }}</td>
                                                <td>{{ showAmount(variant_stock_quantity($product->id, request('warehouse'), $variant->pivot->variation_id, $variant->pivot->value) * $variant->pivot->price) }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    @else
                                        {{ showAmount(stock_quantity($product->id, request('warehouse')) * $product->price) }}
                                    @endif
                                    
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
