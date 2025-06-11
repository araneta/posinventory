@extends('layouts.app')

@section('title', __('Expense'))


@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('All Expense') }}</h5>
        </div>
        @can('create-expense')
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

    <div class="card custom-card {{ $expenses->count() <= 0 ? 'text-center' : '' }}">
        <div class="card-header justify-content-between">
            @include('includes.__table_header')
        </div>
        <div class="card-body">
            @if ($expenses->count() > 0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Type') }}</th>
                                <th scope="col">{{ __('Date') }}</th>
                                <th scope="col">{{ __('Amount') }}</th>
                                <th scope="col">{{ __('Note') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>
                                        <strong>{{ $expense->type->name }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $expense->date }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ showAmount($expense->amount) }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $expense->note }}</strong>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 flex-wrap">
                                            @can('edit-expense')
                                                <button data-bs-toggle="modal" data-bs-target="#modal"
                                                class="btn btn-primary btn-icon rounded-pill btn-wave btn-sm editBtn"
                                                data-id="{{ $expense->id }}" data-name="{{ $expense->name }}"
                                                data-amount="{{ $expense->amount }}" data-note="{{ $expense->note }}"
                                                data-type="{{ $expense->type_id }}" data-date="{{ $expense->date }}"><i
                                                    class="ri-edit-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit') }}"></i></button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $expenses->links('includes.__pagination') }}
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
                <form action="{{ route('expense.store') }}" method="POST" id="form">
                    @csrf
                    <div id="method_sec">
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="type" class="form-label fs-14 text-dark">{{ __('Expense Type') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" name="type" id="type">
                                <option selected disabled>{{ __('-- Select Type --') }}</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label fs-14 text-dark">{{ __('Amount') }} <span
                                    class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">$</span>
                                <input type="text" class="form-control" placeholder="Enter Amount" aria-label="Username"
                                    aria-describedby="basic-addon1" id="amount" name="amount">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label fs-14 text-dark">{{ __('Date') }} </label>
                            <input type="date" class="form-control" id="date" name="date"
                                placeholder="Choose Date">
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label fs-14 text-dark">{{ __('Note') }} </label>
                            <textarea name="note" id="note" cols="30" rows="2" placeholder="Write Note" class="form-control"></textarea>
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
                $('#title').html('{{ __('Add Expense') }}');
                $('#submitBtn').html('{{ __('Save changes') }}');
                $('#name').val('');
                $('#address').val('');
            })

            //Edit Event
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let date = $(this).data('date');
                let amount = $(this).data('amount');
                let note = $(this).data('note');
                let type = $(this).data('type');
                $("#title").html('{{ __('Edit Expense') }}')
                $("#name").val(name)
                $("#date").val(date)
                $("#amount").val(amount)
                $("#note").val(note)
                $("#type").val(type)

                $('#submitBtn').html('{{ __('Update') }}');
                var url = '{{ route('expense.update', ':id') }}';
                url = url.replace(':id', id);
                $('#form').attr('action', url);
                $('#method_sec').html('<input type="hidden" name="_method" value="PUT">')
            })
        })
    </script>
@endpush
