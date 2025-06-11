<div class="table-responsive">
    <table class="table text-nowrap">
        <thead>
        <tr>
            <th scope="col">{{ __('Variation Name') }}</th>
            <th scope="col">{{ __('Variation Value') }}</th>
            <th scope="col">{{ __('Price') }}</th>
            <th scope="col">{{ __('Sale Price') }}</th>
            <th scope="col">{{ __('Alert Qty') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($product->variants as $variant)
            <tr>
                <th scope="row">{{ $variant->name }}</th>
                <td>{{ $variant->pivot->value }}</td>
                <td>{{ showAmount($variant->pivot->price) }}</td>
                <td>{{ showAmount($variant->pivot->sale_price) }}</td>
                <td>{{ $variant->pivot->alert_quantity }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
