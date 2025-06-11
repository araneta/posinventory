<div class="modal fade" id="addBrandModal" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{ __('Add Brand') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="brand_name" class="form-label fs-14 text-dark">{{ __('Brand Name') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="brand_name" name="name"
                           placeholder="Enter brand name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="add_brand">{{ __('Add Brand') }}</button>
            </div>
        </div>
    </div>
</div>
<?php
