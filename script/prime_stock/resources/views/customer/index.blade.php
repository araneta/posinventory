@extends('layouts.app')

@section('title', __('Customer'))


@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Customer') }}</h5>
        </div>
        @can('create-customer')
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

    <div class="card custom-card {{ $customers->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Name|Address') }}</th>
                                <th scope="col">{{ __('Mobile|Email') }}</th>
                                <th scope="col">{{ __('Receivable') }}</th>
                                <th scope="col">{{ __('Payable') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>
                                        <strong>{{ $customer->name }}</strong><br>
                                        <strong>{{ $customer->address }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $customer->phone }}</strong><br>
                                        <strong>{{ $customer->email }}</strong>
                                    </td>
                                    <td>
                                        {{ showAmount($customer->total_receivable) }}
                                    </td>
                                    <td>
                                        {{ showAmount($customer->total_payable) }}
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 flex-wrap">
                                            @can('edit-customer')
                                                <button data-bs-toggle="modal" data-bs-target="#modal"
                                                    class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                    data-id="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                                    data-address="{{ $customer->address }}"
                                                    data-email="{{ $customer->email }}" data-phone="{{ $customer->phone }}"><i
                                                        class="ri-edit-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit') }}"></i></button>
                                            @endcan
                                            @can('notify-customer')
                                                <a href="{{ route('customer.notify', $customer->id) }}"
                                                    class="btn btn-info btn-icon btn-wave btn-sm" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Notify Customer') }}"><i
                                                        class="ri-notification-line"></i></a>
                                            @endcan
                                            @if ($customer->total_payable > 0 || $customer->total_receivable > 0)
                                                @can('payment-customer')
                                                    <a href="{{ route('customer.payment', $customer->id) }}"
                                                        class="btn btn-info btn-icon btn-wave btn-sm" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="{{ __('Payment') }}"><i
                                                            class="ri-money-dollar-circle-line"></i></a>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $customers->links('includes.__pagination') }}
            @else
                @include('includes.__empty_table')
            @endif

        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="title"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.store') }}" method="POST" id="form">
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
                            <label for="email" class="form-label fs-14 text-dark">{{ __('Email') }} </label>
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
                            <label for="address" class="form-label fs-14 text-dark">{{ __('Address') }} </label>
                            <textarea name="address" placeholder="Enter address" id="address" cols="30" rows="2" class="form-control"></textarea>
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
                $('#title').html('{{ __('Add Customer') }}');
                $('#submitBtn').html('{{ __('Save changes') }}');
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
                $("#title").html('{{ __('Edit Customer') }}')
                $("#name").val(name)
                $("#address").val(address)
                $("#email").val(email)
                $("#phone").val(phone)

                $('#submitBtn').html('Update');
                var url = '{{ route('customer.update', ':id') }}';
                url = url.replace(':id', id);
                $('#form').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })
        })
    </script>
@endpush
