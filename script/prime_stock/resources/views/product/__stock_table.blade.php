<div class="table-responsive">
    <table class="table text-nowrap table-bordered">
        <thead>
            <tr>
                <th scope="col">{{ __('Warehouse Name') }}</th>
                <th scope="col">{{ __('Stock') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($warehouses as $warehouse)
                <tr>
                    <td scope="row">{{ $warehouse->name }}</td>
                    <td>
                        @if ($product->product_type == 'variation')
                            <table class="variant_table">
                                <tr>
                                    <th>{{ __('Variant Name')}}</th>
                                    <th>{{ __('Variant Value')}}</th>
                                    <th>{{ __('Stock')}}</th>
                                </tr>
                                @foreach ($product->variants as $variant)
                                <tr>
                                    <td>{{ $variant->name }}</td>
                                    <td>{{ $variant->pivot->value }}</td>
                                    <td>{{ variant_stock_quantity($product->id, $warehouse->id, $variant->pivot->variation_id, $variant->pivot->value) }}{{ $product->unit->name }}</td>
                                </tr>
                                @endforeach
                            </table>
                        @else
                            {{ stock_quantity($product->id, $warehouse->id) }}{{ $product->unit->name }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
