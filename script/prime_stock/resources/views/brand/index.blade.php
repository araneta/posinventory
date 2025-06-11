@extends('layouts.app')

@section('title', __('Brand'))

@section('content')
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card custom-card collapse-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0" id="title">
                        {{ __('Add Brand') }}
                    </div>
                    <div>
                        <a href="{{ route('brand.index') }}" class="mx-2"><i class="ri-refresh-line fs-18"></i></a>
                        <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                            <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse show" id="collapseExample">
                    <form action="{{ route('brand.store') }}" method="POST" id="brandForm">
                        @csrf
                        <div id="method_sec">
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label fs-14 text-dark">{{ __('Brand Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter brand name">
                            </div>
                            <div class="mb-3">
                                <label for="form-password"
                                    class="form-label fs-14 text-dark me-2">{{ __('Status') }}</label>
                                <input type="radio" class="btn-check" value="1" name="status"
                                    id="success-outlined" />
                                <label class="btn btn-outline-success m-1"
                                    for="success-outlined">{{ __('Active') }}</label>
                                <input type="radio" class="btn-check" value="0" name="status" id="danger-outlined"
                                    checked />
                                <label class="btn btn-outline-danger m-1" for="danger-outlined">{{ __('Deactive') }}</label>
                            </div>
                        </div>
                        @can('create-brand')
                            <div class="card-footer">
                                <button class="btn btn-primary" type="submit">{{ __('Save Changes') }}</button>
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card custom-card {{ $brands->count() <= 0 ? 'text-center' : '' }}">
                <div class="card-header justify-content-between">
                    @include('includes.__table_header')
                </div>
                <div class="card-body">
                    @if ($brands->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('Brand Name') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Product') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brands as $brand)
                                        <tr>
                                            <td>{{ $brand->name }}</td>
                                            <td>
                                                @if ($brand->status)
                                                    <span
                                                        class="badge rounded-pill bg-success-gradient p-2">{{ __('Active') }}</span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill bg-danger-gradient p-2">{{ __('Deactivate') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-dark">{{ $brand->products->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="hstack gap-2 flex-wrap">
                                                    @can('edit-brand')
                                                        <a href="javascript:void(0);" data-id="{{ $brand->id }}"
                                                            data-name="{{ $brand->name }}" data-status="{{ $brand->status }}"
                                                            class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Edit') }}"><i class="ri-edit-line"></i></a>
                                                    @endcan
                                                    @can('delete-brand')
                                                        <button data-id="{{ $brand->id }}"
                                                            class="btn btn-danger btn-icon rounded-pill btn-wave btn-sm dltBtn"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}"
                                                            {{ $brand->products->count() > 0 ? 'disabled' : '' }}><i
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
                        {{ $brands->links('includes.__pagination') }}
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
                let status = $(this).data('status');
                $("#title").html('{{ __('Edit Brand') }}')
                $("#name").val(name)
                status === 1 ? $('#success-outlined').attr('checked', 'checked') : $('#danger-outlined')
                    .attr('checked', 'checked');

                var url = '{{ route('brand.update', ':id') }}';
                url = url.replace(':id', id);
                $('#brandForm').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })


            //Delete Event
            $(document).on('click', '.dltBtn', function() {
                let onOk = () => {
                    var id = $(this).data('id');
                    var url = '{{ route('brand.destroy', ':id') }}';
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
