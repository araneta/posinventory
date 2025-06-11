@extends('layouts.app')

@section('title', __('Email Settings'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Email Settings') }}</h5>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="javascript:void();" class="btn btn-danger label-btn" data-bs-toggle="modal"
                   data-bs-target="#testMailModal">
                    <i class="ri-mail-line label-btn-icon me-2"></i>{{ __('Test Mail') }}
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->
    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <form action="{{ route('settings.email.update') }}" class="row g-3" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mail_driver" class="form-label fs-14 text-dark">{{ __('Mail Driver') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_driver" name="mail_driver"
                                       value="{{ settings('mail_driver') ?? 'smtp' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mail_host" class="form-label fs-14 text-dark">{{ __('Mail Host') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_host" name="mail_host"
                                       value="{{ settings('mail_host') }}" placeholder="Write mail host">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mail_from_name" class="form-label fs-14 text-dark">{{ __('Mail From Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_from_name" name="mail_from_name"
                                       value="{{ settings('mail_from_name') }}" placeholder="Write Mailer from name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mail_from_address" class="form-label fs-14 text-dark">{{ __('Mail From Address') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_from_address" name="mail_from_address"
                                       value="{{ settings('mail_from_address') }}" placeholder="Write mailer from address">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mail_port" class="form-label fs-14 text-dark">{{ __('Mail Port') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_port" name="mail_port"
                                       value="{{ settings('mail_port') }}" placeholder="Write Mail port">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mail_username" class="form-label fs-14 text-dark">{{ __('Mail Username') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_username" name="mail_username"
                                       value="{{ settings('mail_username') }}" placeholder="Write mail username">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mail_password" class="form-label fs-14 text-dark">{{ __('Mail Password') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_password" name="mail_password"
                                       value="{{ settings('mail_password') }}" placeholder="Write mailer password">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mail_encryption" class="form-label fs-14 text-dark">{{ __('Mail Encryption') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_encryption" name="mail_encryption"
                                       value="{{ settings('mail_encryption') }}" placeholder="eg: tls, ssl">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

{{--    Modal--}}
    <div class="modal fade" id="testMailModal" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel1">{{ __('Test Email') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.email.test') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label fs-14 text-dark">{{ __('Email') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email"
                                   value="{{ settings('email') }}" placeholder="Write email address">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Send Mail') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

