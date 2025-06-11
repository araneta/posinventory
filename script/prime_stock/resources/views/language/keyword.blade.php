@extends('layouts.app')

@section('title', __('Language Keywords'))

@push('css')
    <style>
        .table td,
        .table th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
        }
    </style>
@endpush

@section('content')
    <div class="card mt-3 {{ $translations->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header">
            @include('language.include.__table_header')
        </div>
        <div class="card-body">
            @if($translations->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('KEY') }}</th>
                            <th scope="col">en</th>
                            <th scope="col">{{ $language }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($translations as $type => $items)
                            @foreach($items as $group => $translations)
                                @foreach($translations as $key => $value)
                                    @if(!is_array($value['en']))
                                        <tr>
                                            <td scope="col">{{ $key }}</td>
                                            <td scope="col">{{ $value['en'] }}</td>
                                            <td scope="col">
                                                {{ $value[$language] }}
                                            </td>
                                            <td scope="col">
                                                <div class="hstack gap-2 flex-wrap">
                                                    <button
                                                        type="button"
                                                        data-language="{{ $language }}"
                                                        data-group="{{ $group }}"
                                                        data-key="{{ $key }}"
                                                        data-value="{{ $value[$language] }}"
                                                        class="btn btn-primary btn-icon rounded-pill btn-wave edit-language-keyword"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editKeyword"
                                                    ><i class="ri-edit-line"></i
                                                        ></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>

    {{--    Modal--}}
    <div class="modal fade" id="editKeyword" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel1">{{ __('Edit Keyword') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form action="{{ route('language-keyword-update') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="site-input-groups mb-2">
                            <label class="key-label mb-3"></label>
                            <input type="hidden" class="form-control key-key" name="key">
                            <input type="text" class="form-control key-value" placeholder="Enter key value" name="value">
                            <input type="hidden" class="form-control key-group" name="group">
                            <input type="hidden" class="form-control key-language" name="language">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.edit-language-keyword').on('click',function (e) {

                var key = $(this).data('key');
                var value = $(this).data('value');
                var group = $(this).data('group');
                var language = $(this).data('language');


                $('.key-label').html(key);
                $('.key-key').val(key);
                $('.key-value').val(value);
                $('.key-group').val(group);
                $('.key-language').val(language);

                $('#editKeyword').modal('toggle')
            })


        })(jQuery);
    </script>
@endpush
