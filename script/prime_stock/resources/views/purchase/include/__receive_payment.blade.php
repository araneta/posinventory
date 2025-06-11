<div class="modal fade" id="paymentModal" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{ __('Receive Payment') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <form action="{{ route('purchase.receive.payment') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="purchase_id_modal">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="invoice_no_modal" class="form-label fs-14 text-dark">{{ __('Invoice No') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="invoice_no_modal" name="invoice_no"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label for="receivable_amount_modal"
                               class="form-label fs-14 text-dark">{{ __('Receivable Amount') }} </label>
                        <div class="input-group">
                            <span class="input-group-text" id="receivable_amount_addon">$</span>
                            <input type="number" class="form-control form-control-lg receivable_amount"
                                   name="receivable_amount" value="0.00"
                                   aria-label="Currency" aria-describedby="receivable_amount_addon"
                                   id="receivable_amount_modal" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="received_amount_modal"
                               class="form-label fs-14 text-dark">{{ __('Received Amount') }} </label>
                        <div class="input-group">
                            <span class="input-group-text" id="received_amount_modal_addon">$</span>
                            <input type="number" class="form-control form-control-lg payable_amount"
                                   name="received_amount" value="0.00"
                                   aria-label="Currency" aria-describedby="received_amount_modal_addon"
                                   id="received_amount_modal">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
