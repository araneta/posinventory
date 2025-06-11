<div class="modal fade" id="paymentModal" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{ __('Give Payment') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <form action="{{ route('purchase.give.payment') }}" method="POST">
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
                        <label for="payable_amount_modal"
                               class="form-label fs-14 text-dark">{{ __('Payable Amount') }} </label>
                        <div class="input-group">
                            <span class="input-group-text" id="payable_amount_addon">$</span>
                            <input type="number" class="form-control form-control-lg payable_amount"
                                   name="payable_amount" value="0.00"
                                   aria-label="Currency" aria-describedby="payable_amount_addon"
                                   id="payable_amount_modal" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="paying_amount_modal"
                               class="form-label fs-14 text-dark">{{ __('Paying Amount') }} </label>
                        <div class="input-group">
                            <span class="input-group-text" id="paying_amount_modal_addon">$</span>
                            <input type="number" class="form-control form-control-lg payable_amount"
                                   name="paying_amount" value="0.00"
                                   aria-label="Currency" aria-describedby="paying_amount_modal_addon"
                                   id="paying_amount_modal">
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
