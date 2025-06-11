@extends('layouts.app')

@section('title', __('Variations'))

@section('content')
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card custom-card collapse-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0" id="title">
                        {{ __('Add Variations') }}
                    </div>
                    <div>
                        <a href="{{ route('variation.index') }}" class="mx-2"><i class="ri-refresh-line fs-18"></i></a>
                        <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                            <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse show" id="collapseExample">
                    <form action="{{ route('variation.store') }}" method="POST" id="unitForm">
                        @csrf
                        <div id="method_sec">
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label fs-14 text-dark">{{ __('Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter name">
                            </div>
                            <div class="mb-3">
                                <label for="values" class="form-label fs-14 text-dark">{{ __('Values') }} <span
                                        class="text-danger">*</span></label>
                                <div class="row default_html">
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="values" name="values[]"
                                            placeholder="Enter values">
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="addMore"><i
                                                class="ri-add-line"></i></button>
                                    </div>
                                </div>
                                <div id="insert_field">

                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            @can('create-variation')
                                <button class="btn btn-primary" type="submit">{{ __('Save Changes') }}</button>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card custom-card {{ $variations->count() <= 0 ? 'text-center' : '' }}">
                <div class="card-header justify-content-between">
                    @include('includes.__table_header')
                </div>
                <div class="card-body">
                    @if ($variations->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Values') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($variations as $variation)
                                        <tr>
                                            <td>{{ $variation->name }}</td>
                                            <td>
                                                {!! arrayBadge($variation->values) !!}
                                            </td>
                                            <td>
                                                <div class="hstack gap-2 flex-wrap">
                                                    @can('edit-variation')
                                                        <a href="javascript:void(0);" data-id="{{ $variation->id }}"
                                                            data-name="{{ $variation->name }}"
                                                            data-values="{{ arrayToString($variation->values) }}"
                                                            class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Edit') }}"><i class="ri-edit-line"></i></a>
                                                    @endcan
                                                    @can('delete-variation')
                                                        <button data-id="{{ $variation->id }}"
                                                            class="btn btn-danger btn-icon rounded-pill btn-wave btn-sm dltBtn"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}"><i
                                                                class="ri-delete-bin-5-line"></i></button>
                                                    @endcan
                                                    <form action="" id="dltForm" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $variations->links('includes.__pagination') }}
                    @else
                        @include('includes.__empty_table')
                    @endif

                </div>
            </div>
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
            //Edit Event
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let values = $(this).data('values');
                $("#title").html('{{ __('Edit Units') }}')
                $("#name").val(name)
                $('#insert_field').html('');
                $('.default_html').html('');
                // value is comma separated string and first item show add more button but another item show remove button
                values = values.split(',');
                values.forEach(function(value, index) {
                    let html;
                    if (index === 0) {
                        html =
                            '<div class="row mt-2"><div class="col-10"><input type="text" class="form-control" id="values" name="values[]" value="' +
                            value +
                            '" placeholder="Values"></div><div class="col-2"><button type="button" class="btn btn-primary btn-sm" id="addMore"><i class="ri-add-line"></i></button></div></div>';
                    } else {
                        html =
                            '<div class="row mt-2"><div class="col-10"><input type="text" class="form-control" id="values" name="values[]" value="' +
                            value +
                            '" placeholder="Values"></div><div class="col-2"><button type="button" class="btn btn-danger btn-sm" id="removeField"><i class="ri-close-line"></i></button></div></div>';
                    }
                    $('#insert_field').append(html);
                })

                let url = '{{ route('variation.update', ':id') }}';
                url = url.replace(':id', id);
                $('#unitForm').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })

            // add more field
            $(document).on('click', '#addMore', function() {
                const html =
                    '<div class="row mt-2"><div class="col-10"><input type="text" class="form-control" id="values" name="values[]" placeholder="Values"></div><div class="col-2"><button type="button" class="btn btn-danger btn-sm" id="removeField"><i class="ri-close-line"></i></button></div></div>';
                $('#insert_field').append(html);
            })
            // remove field
            $(document).on('click', '#removeField', function() {
                $(this).closest('.row').remove();
            })

            //Delete Event
            $(document).on('click', '.dltBtn', function() {
                let onOk = () => {
                    const id = $(this).data('id');
                    let url = '{{ route('variation.destroy', ':id') }}';
                    url = url.replace(':id', id);
                    $('#dltForm').attr('action', url);
                    $('#dltForm').submit();
                };
                let onCancel = () => {
                    notifier.info('Your Item is safe')
                };
                notifier.confirm('{{ __('Are you sure you want to delete the items') }}', onOk, onCancel)
            })
        })
    </script>
@endpush
