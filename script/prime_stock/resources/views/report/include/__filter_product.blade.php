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
                        <label for="type" class="form-label fs-14 text-dark">{{ __('Product Type') }} <span
                                class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="type" id="type">
                            <option selected disabled>{{ __('-- Select Type --') }}</option>
                            <option value="single" @selected(request('type') == 'single')>{{ __('Single') }}</option>
                            <option value="variation" @selected(request('type') == 'variation')>{{ __('Variation') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mt-4">
                    <button type="submit" class="btn btn-primary label-btn mt-2 w-40">
                        <i class="ri-filter-2-fill label-btn-icon me-2"></i>{{ __('Filter') }}
                    </button>
                    <a href="{{ route('report.product') }}" class="btn btn-danger label-btn mt-2 w-40">
                        <i class="ri-refresh-line label-btn-icon me-2"></i>{{ __('Reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
