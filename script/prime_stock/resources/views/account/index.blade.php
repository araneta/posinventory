@extends('layouts.app')

@section('title', __('Accounts'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Accounts') }}</h5>
        </div>
        @can('create-account')
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

    <div class="card custom-card {{ $accounts->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($accounts->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Account Number') }}</th>
                                <th scope="col">{{ __('Opening Balance') }}</th>
                                <th scope="col">{{ __('Balance') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr>
                                    <td>
                                        <strong>{{ $account->name }}</strong>
                                    </td>
                                    <td>
                                        {{ $account->account_number ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ showAmount($account->opening_balance) }}
                                    </td>
                                    <td>
                                        {{ showAmount(accountBalance($account->id)) }}
                                    </td>
                                    <td>
                                        @can('edit-account')
                                            <div class="hstack gap-2 flex-wrap">
                                                <button data-bs-toggle="modal" data-bs-target="#modal"
                                                    class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                    data-id="{{ $account->id }}" data-name="{{ $account->name }}"
                                                    data-number="{{ $account->account_number }}"
                                                    data-balance="{{ $account->opening_balance }}"><i class="ri-edit-line"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit') }}"></i></button>
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $accounts->links('includes.__pagination') }}
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
                <form action="{{ route('account.store') }}" method="POST" id="form">
                    @csrf
                    <div id="method_sec">
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fs-14 text-dark">{{ __('Account Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="account_number" class="form-label fs-14 text-dark">{{ __('Account Number') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="account_number" name="account_number"
                                placeholder="Enter account number">
                        </div>
                        <div class="mb-3">
                            <label for="opening_balance" class="form-label fs-14 text-dark">{{ __('Opening Balance') }}
                                <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="opening_balance" name="opening_balance"
                                placeholder="Enter opening balance">
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
                $('#title').html('{{ __('Add Account') }}');
                $('#submitBtn').html('{{ __('Save Changes') }}');
                $('#name').val('');
                $('#account_number').val('');
                $('#opening_balance').val('');
            })

            //Edit Event
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let account_number = $(this).data('number');
                let opening_balance = $(this).data('balance');
                $("#title").html('{{ __('Edit Account') }}')
                $("#name").val(name)
                $("#account_number").val(account_number)
                $("#opening_balance").val(opening_balance)
                $('#submitBtn').html('Update');
                var url = '{{ route('account.update', ':id') }}';
                url = url.replace(':id', id);
                $('#form').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })
        })
    </script>
@endpush
