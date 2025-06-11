@extends('layouts.app')

@section('title', __('Role List'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Role List') }}</h5>
        </div>
        @can('create-role')
        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a class="btn btn-primary label-btn" href="{{ route('role.create') }}">
                    <i class="ri-add-circle-line label-btn-icon me-2"></i>{{ __('Add New') }}
                </a>
            </div>
        </div>
        @endcan
    </div>
    <!-- Page Header Close -->

    <div class="card custom-card {{ $roles->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if($roles->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Permission Group') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>
                                    <strong>{{ $role->name }}</strong>
                                </td>
                                <td>
                                    <div class="hstack gap-2 flex-wrap">
                                        @foreach($role->permissions->groupBy('group_name') as $key => $permission)
                                            <span class="badge rounded-pill bg-outline-info p-2">{{ ucwords($key) }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="hstack gap-2 flex-wrap">
                                        @can('edit-role')
                                        <a
                                            href="{{ route('role.edit', $role) }}"
                                            class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                        ><i class="ri-edit-line" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}"></i
                                            ></a>
                                        @endcan
                                        @can('delete-role')
                                        <button
                                            data-id="{{ $role->id }}"
                                            class="btn btn-danger btn-icon rounded-pill btn-wave btn-sm dltBtn"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Delete') }}"
                                            {{ $role->name === 'Super Admin' ? 'disabled' : '' }}
                                        ><i class="ri-delete-bin-5-line"></i
                                            ></button>
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
                {{ $roles->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>

@endsection
@push('script')
    <script>
        $(function () {
            "use strict"
            let options = {
                'position': 'top-right'
            }
            let notifier = new AWN(options);

            //Delete Event
            $(document).on('click', '.dltBtn', function(){
                let onOk = () => {
                    let id = $(this).data('id');
                    let url = '{{ route("role.destroy", ":id") }}';
                    url = url.replace(':id', id);
                    $('#dltForm').attr('action', url);
                    $('#dltForm').submit();
                };
                let onCancel = () => {notifier.info('Your Item is safe')};
                notifier.confirm('{{ __('Are you sure you want to delete the items') }}', onOk,onCancel)
            })
        })
    </script>
@endpush
