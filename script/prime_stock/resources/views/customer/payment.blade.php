@extends('layouts.app')

@section('title', __('Customer Payment Clear'))

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">{{ __('Unsettled Payments with ') }}{{ $customer->name }}</h5>
        </div>

        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pe-1 mb-xl-0">
                <a href="{{ route('customer.index') }}" class="btn btn-danger label-btn">
                    <i class="ri-arrow-go-back-line label-btn-icon me-2"></i>{{ __('Back') }}
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="card custom-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="fs-6">
                        <i class="ri-money-dollar-circle-line text-success me-1"></i>
                        <span class="me-3">{{ __('Receivable') }}</span><strong
                            class="text-success">{{ showAmount($customer->total_receivable) }}</strong>
                    </p>
                    <p class="fs-6">
                        <i class="ri-money-dollar-circle-line text-danger me-1"></i>
                        <span class="me-3">{{ __('Payable') }}</span><strong
                            class="text-danger">{{ showAmount($customer->total_payable) }}</strong>
                    </p>
                </div>
                <div>
                    <button class="btn btn-outline-dark btn-wave label-btn" data-bs-toggle="modal"
                            data-bs-target="#modal" id="addBtn">
                        <i class="ri-money-dollar-circle-fill label-btn-icon me-2"></i>{{ __('Clear Payment') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @php
        $totalPayable = 0;
        $receivabale = 0;
    @endphp
    <div class="card custom-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead class="table-primary">
                    <tr>
                        <th scope="col">{{ __('Invoice No') }}</th>
                        <th scope="col">{{ __('Reason') }}</th>
                        <th scope="col" class="text-end">{{ __('Amount') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customer->receivableData() as $transaction)
                        @php
                            $receivabale += $transaction->dueAmount();
                        @endphp
                        <tr class="text-success">
                            <td>{{ $transaction->invoice_no }}</td>
                            <td>{{ __('Sale') }}</td>
                            <td class="text-end">{{ showAmount($transaction->dueAmount()) }}</td>
                        </tr>
                    @endforeach
                    @foreach($customer->payableData() as $transaction)
                        @php
                            $totalPayable += $transaction->returnDueAmount();
                        @endphp
                        <tr class="text-danger">
                            <td>{{ $transaction->invoice_no }}</td>
                            <td>{{ __('Sale Return') }}</td>
                            <td class="text-end">{{ showAmount($transaction->returnDueAmount()) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="table-danger">
                    <tr>
                        <td colspan="2" class="text-start"><strong>{{ __('Total') }}</strong></td>
                        <td class="text-end">
                            <strong>{{ showAmount($customer->totalReceivable()) }}</strong>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{--Modal--}}
    <div class="modal fade" id="modal" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="title">{{ __('Clear Full Payment With') }} {{ $customer->name }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.payment.clear', $customer->id) }}" method="POST" id="form">
                    @csrf
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <tbody>
                                <tr>
                                    <td>{{ __('Receivable Amount') }}</td>
                                    <td class="text-end">{{ showAmount($receivabale) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Payable Amount') }}</td>
                                    <td class="text-end">{{ showAmount($totalPayable) }}</td>
                                </tr>
                                </tbody>
                                <tfoot class="table-info">
                                <tr>
                                    <td>{{ __('Receivable Amount') }}</td>
                                    <td class="text-end">{{ showAmount($receivabale - $totalPayable) }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="alert alert-primary d-flex align-items-center mt-2" role="alert">
                            <svg class="flex-shrink-0 me-2 svg-primary" xmlns="http://www.w3.org/2000/svg"
                                 height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000">
                                <path d="M0 0h24v24H0V0z" fill="none"/>
                                <path
                                    d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                            </svg>
                            <div>
                                {{ __('If you click on "Clear Payment" button, all receivable and payable dues will be cleared. So be sure before click.') }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('Clear Payment') }}</button>
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
        })
    </script>
@endpush
