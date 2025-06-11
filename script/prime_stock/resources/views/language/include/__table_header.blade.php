<form action="{{ request()->url() }}" method="get">
    <div class="d-flex justify-content-between">
        <div class="card-title">
            <div class="d-flex">
                @include('language.include.__select', ['name' => 'language', 'items' => $languages, 'submit' => true, 'selected' => $language])
                @include('language.include.__select', ['name' => 'group', 'items' => $groups, 'submit' => true, 'selected' => Request::get('group'), 'optional' => true])
            </div>
        </div>
        <div class="prism-toggle">
            <div class="input-group">
                <div class="input-group-text">
                    <i class="ri-search-line"></i>
                </div>
                <label for="search"></label><input type="text" name="search" value="{{ request('search') }}" id="search" class="form-control"
                                                   placeholder="{{ __('Search') }}">
            </div>
        </div>
    </div>
</form>
