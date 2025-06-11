@extends('layouts.app')

@section('title', __('Email Template'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Email Template') }}</h5>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="card custom-card {{ $emailTemplates->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if($emailTemplates->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Subject') }}</th>
                            <th scope="col">{{ __('Type') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($emailTemplates as $template)
                            <tr>
                                <td>
                                    <strong>{{ $template->name }}</strong>
                                </td>
                                <td>
                                    {{ $template->subject }}
                                </td>
                                <td>
                                    {{ ucwords($template->type) }}
                                </td>
                                <td>
                                    <div class="hstack gap-2 flex-wrap">
                                        <a
                                            href="{{ route('email-template.edit', $template) }}"
                                            class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}"
                                        ><i class="ri-edit-line"></i
                                            ></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $emailTemplates->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>
@endsection
