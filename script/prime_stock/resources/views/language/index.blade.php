@extends('layouts.app')

@section('title', __('Language Settings'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Language Settings') }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            @can('sync-language')
                <div class="pe-1 mb-xl-0">
                    <a href="{{ route('language-sync-missing') }}" class="btn btn-dark label-btn">
                        <i class="ri-refresh-line label-btn-icon me-2"></i>{{ __('Sync Missing Translation Keys') }}
                    </a>
                </div>
            @endcan
            @can('create-language')
                <div class="pe-1 mb-xl-0">
                    <a href="{{ route('language.create') }}" class="btn btn-primary label-btn">
                        <i class="ri-add-circle-line label-btn-icon me-2"></i>{{ __('Add New') }}
                    </a>
                </div>
            @endcan
        </div>
    </div>
    <!-- Page Header Close -->
    <div class="card custom-card {{ $languages->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($languages->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Language Name') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($languages as $language)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2 avatar-rounded">
                                                <img src="{{ $language->flag !== null ? asset('assets/images/language/' . $language->flag) : asset('assets/images/language.png') }}"
                                                    alt="img" />
                                            </div>
                                            <div class="ms-2">
                                                <div class="lh-1">
                                                    <span class="fw-bold fs-18">{{ $language->name }}</span>
                                                    @if ($language->is_default)
                                                        <span
                                                            class="badge rounded-pill bg-success ms-2 p-2">{{ __('Default') }}</span>
                                                    @endif
                                                </div>
                                                <div class="lh-1 mt-2">
                                                    <span class="fs-14">{{ $language->locale }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($language->status)
                                            <span
                                                class="badge rounded-pill bg-success-gradient p-2">{{ __('Active') }}</span>
                                        @else
                                            <span
                                                class="badge rounded-pill bg-danger-gradient p-2">{{ __('Deactivate') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 flex-wrap">
                                            @can('translate-language')
                                            <a href="{{ route('language-keyword', $language->locale) }}"
                                                class="btn btn-dark btn-icon rounded-pill btn-wave btn-sm"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Language Keyword') }}"><i class="ri-global-line"></i></a>
                                            @endcan
                                            @can('edit-language')
                                            <a href="{{ route('language.edit', $language) }}"
                                                class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Language') }}"><i class="ri-edit-line"></i></a>
                                            @endcan
                                            @if (!$language->is_default)
                                                <button data-id="{{ $language->id }}"
                                                    class="btn btn-danger btn-icon rounded-pill btn-wave btn-sm dltBtn"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Language') }}"><i
                                                        class="ri-delete-bin-5-line"></i></button>
                                                <form action="" id="dltForm" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $languages->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            "use strict"
            var options = {
                'position': 'top-right'
            }
            var notifier = new AWN(options);
            $(document).on('click', '.dltBtn', function() {
                let onOk = () => {
                    var id = $(this).data('id');
                    var url = '{{ route('language.destroy', ':id') }}';
                    url = url.replace(':id', id);
                    $('#dltForm').attr('action', url);
                    $('#dltForm').submit();
                };
                let onCancel = () => {
                    notifier.info('Your Item is safe')
                };
                notifier.confirm('Are you sure you want to delete the items', onOk, onCancel)
            })
        })
    </script>
@endpush
