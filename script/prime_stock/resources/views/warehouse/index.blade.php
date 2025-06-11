@extends('layouts.app')

@section('title', __('Warehouse'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Warehouse') }}</h5>
        </div>
        @can('create-warehouse')
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="pe-1 mb-xl-0">
                    <button class="btn btn-primary label-btn" data-bs-toggle="modal" data-bs-target="#modal"
                            id="addBtn">
                        <i class="ri-add-circle-line label-btn-icon me-2"></i>{{ __('Add New') }}
                    </button>
                </div>
            </div>
        @endcan
    </div>
    <!-- Page Header Close -->

    <div class="card custom-card {{ $warehouses->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($warehouses->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Address') }}</th>
                            <th scope="col">{{ __('Admin') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($warehouses as $warehouse)
                            <tr>
                                <td>
                                    <strong>{{ $warehouse->name }}</strong>
                                </td>
                                <td>
                                    {{ $warehouse->address }}
                                </td>
                                <td>
                                    @if ($warehouse->staff_id != null)
                                        <span class="fw-bold">{{ $warehouse->staff->name }}</span>
                                        <br>
                                        <span class="text-muted">{{ $warehouse->staff->email }}</span>
                                    @else
                                        <span
                                            class="text-danger">{{ __('No Admin Assign') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($warehouse->status)
                                        <span
                                            class="badge rounded-pill bg-success-gradient p-2">{{ __('Active') }}</span>
                                    @else
                                        <span
                                            class="badge rounded-pill bg-danger-gradient p-2">{{ __('Deactivate') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="hstack gap-2 flex-wrap">
                                        @can('edit-warehouse')
                                            <button data-bs-toggle="modal" data-bs-target="#modal"
                                                    class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                    data-id="{{ $warehouse->id }}"
                                                    data-name="{{ $warehouse->name }}"
                                                    data-address="{{ $warehouse->address }}"
                                                    data-status="{{ $warehouse->status }}"
                                                    data-hasadmin="{{ $warehouse->staff_id }}"
                                                    data-admin="{{ $warehouse->staff }}"
                                            ><i class="ri-edit-line"
                                                                                              data-bs-toggle="tooltip"
                                                                                              data-bs-placement="top"
                                                                                              title="{{ __('Edit') }}"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $warehouses->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="title"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('warehouse.store') }}" method="POST" id="form">
                    @csrf
                    <div id="method_sec">
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fs-14 text-dark">{{ __('Warehouse Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label fs-14 text-dark">{{ __('Address') }} <span
                                    class="text-danger">*</span></label>
                            <textarea name="address" id="address" cols="30" rows="2" placeholder="Write address"
                                      class="form-control"></textarea>
                        </div>
                        <div class="mb-3" id="is_admin_sec">
                            <div class="form-check form-check-md d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" name="is_admin" value="1" id="is_admin">
                                <label class="form-check-label" for="is_admin">
                                    Is Admin?
                                </label>
                            </div>
                        </div>
                        <div id="hidden_sec" class="d-none">
                        <div class="mb-3">
                            <label for="name" class="form-label fs-14 text-dark">{{ __('Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_name" name="user_name"
                                   placeholder="Enter name...">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fs-14 text-dark">{{ __('Email') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email"
                                   placeholder="Enter email...">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fs-14 text-dark">{{ __('Password') }} <span
                                    class="text-danger required_pass">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Password"
                                       aria-label="Recipient's username" id="password" name="password"
                                       aria-describedby="button-addon2">
                                <button class="input-group-text" type="button"
                                        id="generate_password">{{ __('Generate') }}</button>
                            </div>
                            <div id="passwordHelp" class="form-text"></div>
                        </div>
                        </div>
                        <div class="mb-3">
                            <label for="form-password"
                                   class="form-label fs-14 text-dark me-2">{{ __('Status') }}</label>
                            <input type="radio" class="btn-check" value="1" name="status" id="success-outlined"/>
                            <label class="btn btn-outline-success m-1" for="success-outlined">{{ __('Active') }}</label>
                            <input type="radio" class="btn-check" value="0" name="status" id="danger-outlined"
                                   checked/>
                            <label class="btn btn-outline-danger m-1" for="danger-outlined">{{ __('Deactive') }}</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('Save changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(function () {
            "use strict"
            var options = {
                'position': 'top-right'
            }
            var notifier = new AWN(options);
            //Add Event
            $("#addBtn").on('click', function () {
                $('#title').html('{{ __('Add Warehouse') }}');
                $('#submitBtn').html('{{ __('Save Changes') }}');
                $('#name').val('');
                $('#address').val('');
                $("#is_admin").prop('checked', false);
                $('#hidden_sec').addClass('d-none');
                $("#user_name").val('');
                $("#email").val('');
                $("#password").val('');
            })

            //Edit Event
            $(document).on('click', '.editBtn', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let address = $(this).data('address');
                let status = $(this).data('status');
                let has_admin = $(this).data('hasadmin');
                let admin = $(this).data('admin');
                $("#title").html('{{ __('Edit Warehouse') }}')
                $("#name").val(name)
                $("#address").val(address)
                status === 1 ? $('#success-outlined').attr('checked', 'checked') : $('#danger-outlined')
                    .attr('checked', 'checked');
                has_admin != null ? $('#is_admin').attr('checked', 'checked') : '';
                has_admin != null ? $("#hidden_sec").removeClass('d-none') : '';
                $("#user_name").val(admin.name);
                $("#email").val(admin.email);
                $(".required_pass").addClass('d-none')
                $('#submitBtn').html('Update');
                var url = '{{ route('warehouse.update', ':id') }}';
                url = url.replace(':id', id);
                $('#form').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })

            // generate password
            $('#generate_password').on('click', function() {
                let password = Math.random().toString(36).slice(-8);
                $('#password').val(password);
            })

            //is_admin checkbox toggle
            $('#is_admin').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#hidden_sec').removeClass('d-none');
                } else {
                    $('#hidden_sec').addClass('d-none');
                }
            })
        })
    </script>
@endpush
