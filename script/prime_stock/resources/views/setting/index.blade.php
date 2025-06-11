@extends('layouts.app')

@section('title', __('System Settings'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/filepond/filepond.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('System Settings') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->
    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" class="row g-3" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="company_name" class="form-label fs-14 text-dark">{{ __('Company Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="company_name" name="company_name"
                                       value="{{ settings('company_name') }}" placeholder="Enter company name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="company_phone" class="form-label fs-14 text-dark">{{ __('Company Phone') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="company_phone" name="company_phone"
                                       value="{{ settings('company_phone') }}" placeholder="Enter company phone">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="company_email" class="form-label fs-14 text-dark">{{ __('Company Email') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="company_email" name="company_email"
                                       value="{{ settings('company_email') }}" placeholder="Enter company email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="footer_text" class="form-label fs-14 text-dark">{{ __('Footer Text') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="footer_text" name="footer_text"
                                       value="{{ settings('footer_text') }}" placeholder="Enter footer text">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="site_title" class="form-label fs-14 text-dark">{{ __('Site Title') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="site_title" name="site_title"
                                       value="{{ settings('site_title') }}" placeholder="Enter site title">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="currency" class="form-label fs-14 text-dark">{{ __('Currency') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="currency" name="currency"
                                       value="{{ settings('currency') }}" placeholder="Enter currency">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="currency_symbol" class="form-label fs-14 text-dark">{{ __('Currency Symbol') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="currency_symbol" name="currency_symbol"
                                       value="{{ settings('currency_symbol') }}" placeholder="Enter currency symbol">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="timezone" class="form-label fs-14 text-dark">{{ __('Timezone') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="timezone" id="timezone">
                                    <option selected disabled>{{ __('-- Select Timezone --') }}</option>
                                    @foreach(timezone() as $key => $value)
                                        <option value="{{ $value['tzCode'] }}" {{ settings('timezone') == $value['tzCode'] ? 'selected' : '' }}>{{ $value['tzCode'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="record_to_display" class="form-label fs-14 text-dark">{{ __('Record to display per page') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="record_to_display" id="record_to_display">
                                    <option selected disabled>{{ __('-- Select Record to display per page --') }}</option>
                                    @foreach([10, 25, 50, 100] as $value)
                                        <option value="{{ $value }}" {{ settings('record_to_display') == $value ? 'selected' : '' }}>{{ $value }} items per page</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="currency_format" class="form-label fs-14 text-dark">{{ __('Currency Showing Format') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="currency_format" id="currency_format">
                                    <option selected disabled>{{ __('-- Select Currency Format --') }}</option>
                                    <option value="text_symbol" {{ settings('currency_format') == 'text_symbol' ? 'selected' : '' }}>{{ __('Show Currency Text and Symbol Both') }}</option>
                                    <option value="text" {{ settings('currency_format') == 'text' ? 'selected' : '' }}>{{ __('Show Currency Text Only') }}</option>
                                    <option value="symbol" {{ settings('currency_format') == 'symbol' ? 'selected' : '' }}>{{ __('Show Currency Symbol Only') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="default_customer" class="form-label fs-14 text-dark">{{ __('Default Customer') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="default_customer" id="default_customer">
                                    <option selected disabled>{{ __('-- Select Customer --') }}</option>
                                    @foreach($customers as $key => $value)
                                        <option value="{{ $value->id }}" {{ settings('default_customer') == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="default_warehouse" class="form-label fs-14 text-dark">{{ __('Default Warehouse') }} <span
                                        class="text-danger">*</span></label>
                                <select class="js-example-basic-single" name="default_warehouse" id="default_warehouse">
                                    <option selected disabled>{{ __('-- Select Warehouse --') }}</option>
                                    @foreach($warehouses as $key => $value)
                                        <option value="{{ $value->id }}" {{ settings('default_warehouse') == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="company_address" class="form-label fs-14 text-dark">{{ __('Company Address') }} <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" name="company_address" id="company_address" rows="3">{{ settings('company_address') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="logo" class="form-label fs-14 text-dark">{{ __('Logo') }}</label>
                                <input type="hidden" name="logo" id="logo">
                                <input type="file" class="single-fileupload settings-file-upload logo" data-allow-reorder="true" data-max-file-size="3MB">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="favicon" class="form-label fs-14 text-dark">{{ __('Favicon') }}</label>
                                <input type="hidden" name="favicon" id="favicon">
                                <input type="file" class="single-fileupload settings-file-upload favicon" data-allow-reorder="true" data-max-file-size="3MB">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/libs/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script>
        $(function () {
            "use strict"
            var options = {
                'position': 'top-right'
            }
            var notifier = new AWN(options);
            $('.js-example-basic-single').select2();
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
            );
            const logo = document.querySelector('.logo');
            const pond = FilePond.create(logo, {
                allowImagePreview: true,
                imagePreviewHeight: 170,
                styleLoadIndicatorPosition: 'center bottom',
                styleButtonRemoveItemPosition: 'center bottom',
                maxFiles: 1,
                maxFileSize: '3MB',
                server: {
                    process: {
                        url: '{{ route('settings.update.file') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        onload: (response) => {
                            $('#logo').val(response);
                        },
                        onerror: (response) => {
                            notifier.alert('Something went wrong');
                        }
                    }
                },
                files: [
                    {
                        source: '{{ asset('assets/uploads/') }}/{{ settings('logo') }}',
                    }
                ]
            });

            const favicon = document.querySelector('.favicon');
            const faviconPond = FilePond.create(favicon, {
                allowImagePreview: true,
                imagePreviewHeight: 170,
                styleLoadIndicatorPosition: 'center bottom',
                styleButtonRemoveItemPosition: 'center bottom',
                maxFiles: 1,
                maxFileSize: '3MB',
                server: {
                    process: {
                        url: '{{ route('settings.update.file') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        onload: (response) => {
                            $('#favicon').val(response);
                        },
                        onerror: (response) => {
                            notifier.alert('Something went wrong');
                        }
                    }
                },
                files: [
                    {
                        source: '{{ asset('assets/uploads/') }}/{{ settings('favicon') }}',
                    }
                ]
            });

        })
    </script>
@endpush
