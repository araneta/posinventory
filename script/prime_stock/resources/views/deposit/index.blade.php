@extends('layouts.app')

@section('title', __('Deposits'))

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <style>
        .flatpickr-wrapper {
            width: 91% !important;
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Deposits') }}</h5>
        </div>
        @can('create-deposit')
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

    <div class="card custom-card {{ $deposits->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($deposits->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Account') }}</th>
                                <th scope="col">{{ __('Amount') }}</th>
                                <th scope="col">{{ __('Date') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deposits as $deposit)
                                <tr>
                                    <td>
                                        {{ $deposit->account->name }}
                                    </td>
                                    <td>
                                        {{ showAmount($deposit->amount) }}
                                    </td>
                                    <td>
                                        {{ $deposit->date }}
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 flex-wrap">
                                            @can('edit-deposit')
                                                <button data-bs-toggle="modal" data-bs-target="#modal"
                                                    class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                    data-id="{{ $deposit->id }}" data-account="{{ $deposit->account_id }}"
                                                    data-amount="{{ $deposit->amount }}" data-date="{{ $deposit->date }}"
                                                    data-notes="{{ $deposit->notes }}"><i class="ri-edit-line"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit') }}"></i></button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $deposits->links('includes.__pagination') }}
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
                <form action="{{ route('deposit.store') }}" method="POST" id="form">
                    @csrf
                    <div id="method_sec">
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="account" class="form-label fs-14 text-dark">{{ __('Account Name') }} <span
                                    class="text-danger">*</span></label>
                            <select class="js-example-basic-single" name="account" id="account">
                                <option selected disabled>{{ __('-- Select Account --') }}</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label fs-14 text-dark">{{ __('Amount') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="amount" name="amount"
                                placeholder="Enter amount">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label fs-14 text-dark">{{ __('Date') }} <span
                                    class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-text text-muted"><i class="ri-calendar-line"></i></div>
                                    <input type="text" class="form-control" name="date" id="date"
                                        placeholder="Choose date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label fs-14 text-dark">{{ __('Note') }}</label>
                            <textarea name="note" id="note" cols="30" rows="2" placeholder="Enter note" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        $(function() {
            "use strict"
            $('.js-example-basic-single').select2({
                dropdownParent: $('#modal')
            });
            /* To choose date */
            flatpickr("#date", {
                defaultDate: "today",
                static: true
            });
            var options = {
                'position': 'top-right'
            }
            var notifier = new AWN(options);
            //Add Event
            $("#addBtn").on('click', function() {
                $('#title').html('{{ __('Add Deposit') }}');
                $('#submitBtn').html('{{ __('Save changes') }}');
                $('#account').val('');
                $('#amount').val('');
                $('#date').val('');
                $('#note').text('');
            })

            //Edit Event
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                let account = $(this).data('account');
                let amount = $(this).data('amount');
                let date = $(this).data('date');
                let notes = $(this).data('notes');
                $("#title").html('{{ __('Edit Deposit') }}')
                //select account id and set it to select option
                $("#account").val(account).trigger('change')
                $("#amount").val(amount)
                $("#date").val(date)
                $("#note").val(notes)
                $('#submitBtn').html('Update');
                var url = '{{ route('deposit.update', ':id') }}';
                url = url.replace(':id', id);
                $('#form').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })
        })
    </script>
@endpush
