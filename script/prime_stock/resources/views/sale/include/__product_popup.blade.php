<div class="modal fade" id="productPopupModal" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{ __('Select Variation and Quantity') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="product_id" id="product_id">
                <input type="hidden" name="stock_quantity" id="stock_quantity">
                <input type="hidden" name="variation_id" id="variation_id">
                <div class="mb-3">
                    <label for="product_name" class="form-label fs-14 text-dark">{{ __('Product Name') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg" id="product_name" name="product_name"
                           readonly>
                </div>
                <div class="mb-3">
                    <label for="variation_name" class="form-label fs-14 text-dark">{{ __('Product Variation Name') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg" id="variation_name" name="variation_name"
                           readonly>
                </div>
                <div class="mb-3">
                    <label for="current_stock" class="form-label fs-14 text-dark">{{ __('Current Stock') }} <small
                            class="text-danger">(Select Variation then Stock show)</small></label>
                    <input type="text" class="form-control form-control-lg" value="0" id="current_stock" name="current_stock"
                           readonly>
                </div>
                <label for="">
                    <strong>{{ __('Variation Value') }}</strong>
                </label>
                <div id="variation_value_data" class="my-2">

                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label fs-14 text-dark">{{ __('Quantity') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg" id="quantity" name="quantity" value="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}
                </button>
                <button type="submit" class="btn btn-primary" id="insert_modal_data">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
</div>
