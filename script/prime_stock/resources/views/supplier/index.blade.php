@extends('layouts.app')

@section('title', __('Supplier'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Supplier') }}</h5>
        </div>
        @can('create-supplier')
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="pe-1 mb-xl-0">
                    <button class="btn btn-primary label-btn" data-bs-toggle="modal" data-bs-target="#modal" id="addBtn">
                        <i class="ri-add-circle-line label-btn-icon me-2"></i>{{ __('Add New') }}
                    </button>
                </div>
            </div>
        @endcan
    </div>
    <!-- Page Header Close -->

    <div class="card custom-card {{ $suppliers->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($suppliers->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Mobile|Email') }}</th>
                                <th scope="col">{{ __('Payable') }}</th>
                                <th scope="col">{{ __('Receivable') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>
                                        <strong>{{ $supplier->name }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $supplier->phone }}</strong><br>
                                        <strong>{{ $supplier->email }}</strong>
                                    </td>
                                    <td>
                                        {{ showAmount($supplier->total_payable) }}
                                    </td>
                                    <td>
                                        {{ showAmount($supplier->total_receivable) }}
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 flex-wrap">
                                            @can('edit-supplier')
                                                <button data-bs-toggle="modal" data-bs-target="#modal"
                                                    class="btn btn-primary btn-icon btn-wave btn-sm editBtn"
                                                    data-id="{{ $supplier->id }}" data-name="{{ $supplier->name }}"
                                                    data-address="{{ $supplier->address }}"
                                                    data-email="{{ $supplier->email }}" data-phone="{{ $supplier->phone }}"
                                                    data-company="{{ $supplier->company }}"><i class="ri-edit-line"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit') }}"></i></button>
                                            @endcan
                                            @can('payment-supplier')
                                                @if ($supplier->total_payable > 0 || $supplier->total_receivable > 0)
                                                    <a href="{{ route('supplier.payment', $supplier->id) }}"
                                                        class="btn btn-info btn-icon btn-wave btn-sm" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="{{ __('Payment') }}"><i
                                                            class="ri-money-dollar-circle-line"></i></a>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $suppliers->links('includes.__pagination') }}
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
                <form action="{{ route('supplier.store') }}" method="POST" id="form">
                    @csrf
                    <div id="method_sec">
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fs-14 text-dark">{{ __('Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fs-14 text-dark">{{ __('Email') }} <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter email">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label fs-14 text-dark">{{ __('Phone') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                placeholder="Enter phone">
                        </div>
                        <div class="mb-3">
                            <label for="company" class="form-label fs-14 text-dark">{{ __('Company') }} </label>
                            <input type="text" class="form-control" id="company" name="company"
                                placeholder="Enter company">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label fs-14 text-dark">{{ __('Address') }} </label>
                            <textarea name="address" id="address" cols="30" rows="2" placeholder="Write your address"
                                class="form-control"></textarea>
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
        $(function() {
            "use strict"
            var options = {
                'position': 'top-right'
            }
            var notifier = new AWN(options);
            //Add Event
            $("#addBtn").on('click', function() {
                $('#title').html('{{ __('Add Supplier') }}');
                $('#submitBtn').html('{{ __('Save Changes') }}');
                $('#name').val('');
                $('#address').val('');
            })

            //Edit Event
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let address = $(this).data('address');
                let email = $(this).data('email');
                let phone = $(this).data('phone');
                let company = $(this).data('company');
                $("#title").html('{{ __('Edit Supplier') }}')
                $("#name").val(name)
                $("#address").val(address)
                $("#email").val(email)
                $("#phone").val(phone)
                $("#company").val(company)

                $('#submitBtn').html('Update');
                var url = '{{ route('supplier.update', ':id') }}';
                url = url.replace(':id', id);
                $('#form').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })
        })
    </script>
@endpush
