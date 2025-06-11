@extends('layouts.app')

@section('title', __('Add Role'))

@push('css')

@endpush

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        {{ __('Add New Role') }}
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger-light">{{ __('Back') }} <i
                                class="ri-arrow-go-back-line ms-2 d-inline-block align-middle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('role.store') }}" class="row g-3" method="POST">
                        @csrf
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label fs-14 text-dark">{{ __('Role Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Role Name">
                            </div>
                        </div>
                        <hr class="m-0">
                        <h5>{{ __('Set Permission') }}</h5>
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('Permission Group') }}</th>
                                    <th scope="col">{{ __('Permission') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox"
                                                   id="select_all">
                                            <label class="form-check-label"
                                                   for="select_all">
                                                {{ __('Select All') }}
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                @foreach($permissions as $key => $permission)
                                    <tr>
                                        <td>
                                            <strong>{{ ucwords($key) }}</strong>
                                        </td>
                                        <td>
                                            <div class="row">
                                                @foreach($permission as $value)
                                                    <div class="col-md-3">
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="permissions[]" value="{{ $value->id }}"
                                                                   id="flexSwitchCheckDefault">
                                                            <label class="form-check-label"
                                                                   for="flexSwitchCheckDefault">
                                                                {{ titleCase($value->name) }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Add Role') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function () {
            "use strict"
            // select all checkbox
            $('#select_all').on('change', function () {
                $('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
            });
        })
    </script>
@endpush
