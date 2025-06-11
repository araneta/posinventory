<div class="card custom-card border border-primary">
    <div class="card-body">
        <form action="{{ request()->url() }}" method="get">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="category" class="form-label fs-14 text-dark">{{ __('Category') }} <span
                                class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="category" id="category">
                            <option selected disabled>{{ __('-- Select Category --') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="brand" class="form-label fs-14 text-dark">{{ __('Brand') }} <span
                                class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="brand" id="brand">
                            <option selected disabled>{{ __('-- Select Brand --') }}</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" @selected(request('brand') == $brand->id)>{{ $brand->name }}</option>
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
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" @selected(request('unit') == $unit->id)>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="type" class="form-label fs-14 text-dark">{{ __('Product Type') }} <span
                                class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="type" id="type">
                            <option selected disabled>{{ __('-- Select Type --') }}</option>
                            <option value="single" @selected(request('type') == 'single')>{{ __('Single') }}</option>
                            <option value="variation" @selected(request('type') == 'variation')>{{ __('Variation') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status" class="form-label fs-14 text-dark">{{ __('Product Status') }} <span
                                class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="status" id="status">
                            <option selected disabled>{{ __('-- Select Status --') }}</option>
                            <option value="active" @selected(request('status') == 'active')>{{ __('Active') }}</option>
                            <option value="inactive" @selected(request('category') == 'inactive')>{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mt-4">
                    <button type="submit" class="btn btn-primary label-btn mt-2 w-40">
                        <i class="ri-filter-2-fill label-btn-icon me-2"></i>{{ __('Filter') }}
                    </button>
                    <a href="{{ route('product.index') }}" class="btn btn-danger label-btn mt-2 w-40">
                        <i class="ri-refresh-line label-btn-icon me-2"></i>{{ __('Reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
