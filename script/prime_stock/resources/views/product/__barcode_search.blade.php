@foreach($products as $product)
    <div class="card-text">
        <p class="fw-bold mb-0">{{ $product->name }}</p>
        <div class="d-flex justify-content-between">
            <p class="mb-0">Code: {{ $product->code }}</p>
            <p class="mb-0">Sku: {{ $product->sku }}</p>
        </div>
        <div class="d-flex justify-content-between">
            <p class="mb-0">Price: {{ showAmount($product->price) }}</p>
            <p class="mb-0">Stock: {{ $product->quantity }} {{ $product->unit->name }}</p>
        </div>
    </div>
@endforeach
