<div class="card custom-card border border-primary">
    <div class="card-body">
        <form action="{{ request()->url() }}" method="get">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="warehouse" class="form-label fs-14 text-dark">{{ __('Warehouse') }} <span
                                class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="warehouse" id="warehouse">
                            <option selected disabled>{{ __('-- Select Warehouse --') }}</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" @selected(request('warehouse') == $warehouse->id)>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="date" class="form-label fs-14 text-dark">{{ __('Date Range') }} <span
                                class="text-danger">*</span></label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                <input type="text" class="form-control" name="date" id="date"
                                       placeholder="Choose date" value="{{ request('date') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-4">
                    <button type="submit" class="btn btn-primary label-btn mt-2 w-40">
                        <i class="ri-filter-2-fill label-btn-icon me-2"></i>{{ __('Filter') }}
                    </button>
                    <a href="{{ route('report.profit.loss') }}" class="btn btn-danger label-btn mt-2 w-40">
                        <i class="ri-refresh-line label-btn-icon me-2"></i>{{ __('Reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
