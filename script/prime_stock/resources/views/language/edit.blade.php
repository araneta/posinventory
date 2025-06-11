@extends('layouts.app')

@section('title', __('Language Edit'))

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('Edit Language') }}
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url()->previous(); }}" class="btn btn-sm btn-danger-light">{{ __('Back') }} <i
                                class="ri-arrow-go-back-line ms-2 d-inline-block align-middle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('language.update', $language) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label fs-14 text-dark">{{ __('Language Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter language name" value="{{ $language->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="locale" class="form-label fs-14 text-dark">{{ __('Language Code') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="locale" name="locale"
                                   placeholder="{{ __('Eg: en') }}" value="{{ $language->locale }}">
                        </div>
                        <div class="mb-3">
                            <label for="yes"
                                   class="form-label fs-14 text-dark me-2">{{ __('Default Language') }}</label>
                            <input type="radio" class="btn-check" name="is_default" value="1" id="yes" @checked(old('is_default', $language->is_default))/>
                            <label class="btn btn-outline-success m-1" for="yes">{{ __('Yes') }}</label>
                            <input type="radio" class="btn-check" name="is_default" value="0" id="no" @checked(old('is_default', !$language->is_default))/>
                            <label class="btn btn-outline-danger m-1" for="no">{{ __('No') }}</label>
                        </div>

                        <div class="mb-3">
                            <label for="form-password"
                                   class="form-label fs-14 text-dark me-2">{{ __('Status') }}</label>
                            <input type="radio" class="btn-check" value="1" name="status" id="success-outlined" @checked(old('status', $language->status))/>
                            <label class="btn btn-outline-success m-1" for="success-outlined">{{ __('Active') }}</label>
                            <input type="radio" class="btn-check" value="0" name="status" id="danger-outlined" @checked(old('status', !$language->status))/>
                            <label class="btn btn-outline-danger m-1" for="danger-outlined">{{ __('Deactive') }}</label>
                        </div>

                        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

