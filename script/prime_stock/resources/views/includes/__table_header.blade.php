<form action="{{ request()->url() }}" class="d-flex justify-content-between w-100" method="get" id="tableHeaderForm">
    <div class="card-title">
        {{ __('Show') }}
        &nbsp;
        <label>
            <select class="form-select" name="per_page" id="perPage">
                <option value="10" {{ request('per_page') == 10 || settings('record_to_display') == 10 ? 'selected' : '' }}>{{ __('10') }}</option>
                <option value="25" {{ request('per_page') == 25 || settings('record_to_display') == 25 ? 'selected' : '' }}>{{ __('25') }}</option>
                <option value="50" {{ request('per_page') == 50 || settings('record_to_display') == 50 ? 'selected' : '' }}>{{ __('50') }}</option>
                <option value="100" {{ request('per_page') == 100 || settings('record_to_display') == 100 ? 'selected' : '' }}>{{ __('100') }}</option>
            </select>
        </label>
        &nbsp;
        {{__('entries')}}
    </div>
    <div class="prism-toggle">
        <div class="input-group">
            <label for="search"></label><input type="text" name="search" id="search" class="form-control"
                                               placeholder="{{ __('Search') }}" value="{{ request('search') }}">
            <button class="btn btn-secondary" type="submit">
                <i class="ri-search-line"></i>
            </button>
        </div>
    </div>
</form>
@push('script')
    <script>
        (function ($) {
            "use strict";
            $('#perPage').on('change', function () {
                $('#tableHeaderForm').submit();
            });
        })(jQuery);
    </script>
@endpush
