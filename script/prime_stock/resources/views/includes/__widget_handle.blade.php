<div class="modal fade" id="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <form action="{{ route('widget-update') }}" method="POST" id="form">
                @csrf
                <div class="modal-body">
                    <ul class="list-group">
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_products']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Products') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_products" @checked(isset($widget['total_products']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_customers']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Customers') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_customers" @checked(isset($widget['total_customers']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_suppliers']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Suppliers') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_suppliers" @checked(isset($widget['total_suppliers']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_category']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Category') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_category" @checked(isset($widget['total_category']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_sale']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Sale') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_sale" @checked(isset($widget['total_sale']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_sale_amount']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Sale Amount') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_sale_amount" @checked(isset($widget['total_sale_amount']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_sale_return']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Sale Return') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_sale_return" @checked(isset($widget['total_sale_return']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_sale_return_amount']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Sale Return Amount') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_sale_return_amount" @checked(isset($widget['total_sale_return_amount']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_purchase']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Purchase') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_purchase" @checked(isset($widget['total_purchase']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_purchase_amount']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Purchase Amount') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_purchase_amount" @checked(isset($widget['total_purchase_amount']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_purchase_return']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Purchase Return') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_purchase_return" @checked(isset($widget['total_purchase_return']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['total_purchase_return_amount']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Total Purchase Return Amount') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="total_purchase_return_amount" @checked(isset($widget['total_purchase_return_amount']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['purchase_sale_report']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Purchase Sale Report') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="purchase_sale_report" @checked(isset($widget['purchase_sale_report']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['top_selling_product']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Top Selling Product') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="top_selling_product" @checked(isset($widget['top_selling_product']))>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center fw-semibold {{ isset($widget['stock_level_alert']) ? '' : 'text-decoration-line-through' }}">
                            {{ __('Stock Level Alert') }}
                            <div class="form-check form-check-lg form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="stock_level_alert" @checked(isset($widget['stock_level_alert']))>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('Save changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
