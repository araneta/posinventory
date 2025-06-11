<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ route('dashboard') }}" class="header-logo">
            <img src="{{ asset('assets/uploads') }}/{{ settings('logo') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('assets/uploads') }}/{{ settings('favicon') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('assets/uploads') }}/{{ settings('logo') }}" alt="logo" class="desktop-white">
            <img src="{{ asset('assets/uploads') }}/{{ settings('favicon') }}" alt="logo" class="toggle-white">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <i class="side-menu__icon ri-dashboard-line"></i>
            </div>
            <ul class="main-menu">
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">{{ __('Main') }}</span></li>
                <!-- End::slide__category -->

                <!-- Start::slide -->
                @can('dashboard')
                    <li class="slide {{ menuActive('dashboard') }}">
                        <a href="{{ route('dashboard') }}" class="side-menu__item {{ menuActive('dashboard') }}">
                            <i class="side-menu__icon ri-dashboard-line"></i>
                            <span class="side-menu__label">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                @endcan
                @if (checkPermission(['category', 'brand', 'units', 'variation', 'product', 'print-labels']))
                    <li class="slide has-sub {{ submenuActive(['category*', 'brand*', 'units*', 'variation*', 'product*', 'print-labels']) }}">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="side-menu__icon ri-product-hunt-fill"></i>
                            <span class="side-menu__label">{{ __('Product Manage') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @can('list-category')
                                <li class="slide {{ menuActive('category*') }}">
                                    <a href="{{ route('category.index') }}"
                                       class="side-menu__item {{ menuActive('category*') }}">{{ __('Category') }}</a>
                                </li>
                            @endcan
                            @can('list-brand')
                                <li class="slide {{ menuActive('brand*') }}">
                                    <a href="{{ route('brand.index') }}"
                                       class="side-menu__item {{ menuActive('brand*') }}">{{ __('Brand') }}</a>
                                </li>
                            @endcan
                            @can('list-unit')
                                <li class="slide {{ menuActive('units*') }}">
                                    <a href="{{ route('units.index') }}"
                                       class="side-menu__item {{ menuActive('units*') }}">{{ __('Units') }}</a>
                                </li>
                            @endcan
                            @can('list-variation')
                                <li class="slide {{ menuActive('variation*') }}">
                                    <a href="{{ route('variation.index') }}"
                                       class="side-menu__item {{ menuActive('variation*') }}">{{ __('Variation') }}</a>
                                </li>
                            @endcan
                            @can('list-product')
                                <li class="slide {{ menuActive('product*') }}">
                                    <a href="{{ route('product.index') }}"
                                       class="side-menu__item {{ menuActive('product*') }}">{{ __('Product') }}</a>
                                </li>
                            @endcan
                            @can('print-product-labels')
                                <li class="slide {{ menuActive('print-labels') }}">
                                    <a href="{{ route('print-labels') }}"
                                       class="side-menu__item {{ menuActive('print-labels') }}">{{ __('Print Labels') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if (checkPermission(['purchase', 'purchase-return']))
                    <li class="slide has-sub {{ submenuActive(['purchase*', 'purchase-return*']) }}">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="side-menu__icon ri-store-fill"></i>
                            <span class="side-menu__label">{{ __('Purchase') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @can('list-purchase')
                                <li class="slide {{ menuActive('purchase*') }}">
                                    <a href="{{ route('purchase.index') }}"
                                       class="side-menu__item {{ menuActive('purchase*') }}">{{ __('All Purchase') }}</a>
                                </li>
                            @endcan
                            @can('list-purchase-return')
                                <li class="slide {{ menuActive('purchase-return*') }}">
                                    <a href="{{ route('purchase-return.index') }}"
                                       class="side-menu__item {{ menuActive('purchase-return*') }}">{{ __('Purchase Return') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if (checkPermission(['sale', 'sale-return']))
                    <li class="slide has-sub {{ submenuActive(['sale*', 'sale-return*']) }}">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="side-menu__icon ri-shopping-cart-fill"></i>
                            <span class="side-menu__label">{{ __('Sale') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @can('list-sale')
                                <li class="slide {{ menuActive('sale*') }}">
                                    <a href="{{ route('sale.index') }}"
                                       class="side-menu__item {{ menuActive('sale*') }}">{{ __('All Sale') }}</a>
                                </li>
                            @endcan
                            @can('list-sale-return')
                                <li class="slide {{ menuActive('sale-return*') }}">
                                    <a href="{{ route('sale-return.index') }}"
                                       class="side-menu__item {{ menuActive('sale-return*') }}">{{ __('Sale Return') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @can('list-warehouse')
                    @if(!checkWarehouseAdmin())
                        <li class="slide {{ menuActive('warehouse*') }}">
                            <a href="{{ route('warehouse.index') }}"
                               class="side-menu__item {{ menuActive('warehouse*') }}">
                                <i class="side-menu__icon ri-home-5-line"></i>
                                <span class="side-menu__label">{{ __('Warehouse') }}</span>
                            </a>
                        </li>
                    @endif
                @endcan
                @can('list-supplier')
                    <li class="slide {{ menuActive('supplier*') }}">
                        <a href="{{ route('supplier.index') }}" class="side-menu__item {{ menuActive('supplier*') }}">
                            <i class="side-menu__icon ri-user-shared-line"></i>
                            <span class="side-menu__label">{{ __('Supplier') }}</span>
                        </a>
                    </li>
                @endcan
                @can('list-customer')
                    <li class="slide {{ menuActive('customer*') }}">
                        <a href="{{ route('customer.index') }}" class="side-menu__item {{ menuActive('customer*') }}">
                            <i class="side-menu__icon ri-user-follow-fill"></i>
                            <span class="side-menu__label">{{ __('Customer') }}</span>
                        </a>
                    </li>
                @endcan
                @if (checkPermission(['staff', 'role']))
                    <li class="slide has-sub {{ submenuActive(['staff*', 'role*']) }}">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="side-menu__icon ri-group-2-fill"></i>
                            <span class="side-menu__label">{{ __('Staff') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @can('list-staff')
                                <li class="slide {{ menuActive('staff*') }}">
                                    <a href="{{ route('staff.index') }}"
                                       class="side-menu__item {{ menuActive('staff*') }}">{{ __('All Staff') }}</a>
                                </li>
                            @endcan
                            @can('list-role')
                                <li class="slide {{ menuActive('role*') }}">
                                    <a href="{{ route('role.index') }}"
                                       class="side-menu__item {{ menuActive('role*') }}">{{ __('Role') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if (checkPermission(['expense-type', 'expense']))
                    <li class="slide has-sub {{ submenuActive(['expense-type*', 'expense*']) }}">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="side-menu__icon ri-bank-card-2-line"></i>
                            <span class="side-menu__label">{{ __('Expense') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @can('list-expense-type')
                                <li class="slide {{ menuActive('expense-type*') }}">
                                    <a href="{{ route('expense-type.index') }}"
                                       class="side-menu__item {{ menuActive('expense-type*') }}">{{ __('Expense Type') }}</a>
                                </li>
                            @endcan
                            @can('list-expense')
                                <li class="slide {{ menuActive('expense*') }}">
                                    <a href="{{ route('expense.index') }}"
                                       class="side-menu__item {{ menuActive('expense*') }}">{{ __('All Expense') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @can('list-adjustment')
                    <li class="slide {{ menuActive('adjustment*') }}">
                        <a href="{{ route('adjustment.index') }}"
                           class="side-menu__item {{ menuActive('adjustment*') }}">
                            <i class="side-menu__icon ri-scales-line"></i>
                            <span class="side-menu__label">{{ __('Adjustment') }}</span>
                        </a>
                    </li>
                @endcan
                @can('list-transfer')
                    <li class="slide {{ menuActive('transfer*') }}">
                        <a href="{{ route('transfer.index') }}" class="side-menu__item {{ menuActive('transfer*') }}">
                            <i class="side-menu__icon ri-text-wrap"></i>
                            <span class="side-menu__label">{{ __('Transfer') }}</span>
                        </a>
                    </li>
                @endcan
                @can('list-account')
                    <li class="slide {{ menuActive('account*') }}">
                        <a href="{{ route('account.index') }}" class="side-menu__item {{ menuActive('account*') }}">
                            <i class="side-menu__icon ri-bank-card-line"></i>
                            <span class="side-menu__label">{{ __('Account') }}</span>
                        </a>
                    </li>
                @endcan
                @can('list-deposit')
                    <li class="slide {{ menuActive('deposit*') }}">
                        <a href="{{ route('deposit.index') }}" class="side-menu__item {{ menuActive('deposit*') }}">
                            <i class="side-menu__icon ri-wallet-2-line"></i>
                            <span class="side-menu__label">{{ __('Deposit') }}</span>
                        </a>
                    </li>
                @endcan
                @can('list-transaction')
                    <li class="slide {{ menuActive('transaction*') }}">
                        <a href="{{ route('transaction.index') }}"
                           class="side-menu__item {{ menuActive('transaction*') }}">
                            <i class="side-menu__icon ri-arrow-left-right-line"></i>
                            <span class="side-menu__label">{{ __('Transaction') }}</span>
                        </a>
                    </li>
                @endcan
                @if(checkPermission(['report']))
                    <li class="slide has-sub {{ submenuActive(['report.profit.loss', 'report.stock', 'report.payment.supplier', 'report.payment.customer', 'report.purchase', 'report.sale', 'report.product', 'report.payment.sale', 'report.payment.purchase', 'report.payment.sale.return', 'report.payment.purchase.return']) }}">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="side-menu__icon ri-database-2-line"></i>
                            <span class="side-menu__label">{{ __('Reports') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @can('profit-loss-report')
                                <li class="slide {{ menuActive('report.profit.loss') }}">
                                    <a href="{{ route('report.profit.loss') }}"
                                       class="side-menu__item {{ menuActive('report.profit.loss') }}">{{ __('Profit Loss Report') }}</a>
                                </li>
                            @endcan
                            @can('stock-report')
                                <li class="slide {{ menuActive('report.stock') }}">
                                    <a href="{{ route('report.stock') }}"
                                       class="side-menu__item {{ menuActive('report.stock') }}">{{ __('Stock Report') }}</a>
                                </li>
                            @endcan
                            @can('supplier-payment-report')
                                <li class="slide {{ menuActive('report.payment.supplier') }}">
                                    <a href="{{ route('report.payment.supplier') }}"
                                       class="side-menu__item {{ menuActive('report.payment.supplier') }}">{{ __('Supplier Payment') }}</a>
                                </li>
                            @endcan
                            @can('customer-payment-report')
                                <li class="slide {{ menuActive('report.payment.customer') }}">
                                    <a href="{{ route('report.payment.customer') }}"
                                       class="side-menu__item {{ menuActive('report.payment.customer') }}">{{ __('Customer Payment') }}</a>
                                </li>
                            @endcan
                            @can('purchase-report')
                                <li class="slide {{ menuActive('report.purchase') }}">
                                    <a href="{{ route('report.purchase') }}"
                                       class="side-menu__item {{ menuActive('report.purchase') }}">{{ __('Purchase Report') }}</a>
                                </li>
                            @endcan
                            @can('sales-report')
                                <li class="slide {{ menuActive('report.sale') }}">
                                    <a href="{{ route('report.sale') }}"
                                       class="side-menu__item {{ menuActive('report.sale') }}">{{ __('Sale Report') }}</a>
                                </li>
                            @endcan
                            @can('product-report')
                                <li class="slide {{ menuActive('report.product') }}">
                                    <a href="{{ route('report.product') }}"
                                       class="side-menu__item {{ menuActive('report.product') }}">{{ __('Product Report') }}</a>
                                </li>
                            @endcan
                            @can('payment-sale-report')
                                <li class="slide {{ menuActive('report.payment.sale') }}">
                                    <a href="{{ route('report.payment.sale') }}"
                                       class="side-menu__item {{ menuActive('report.payment.sale') }}">{{ __('Payment Sale') }}</a>
                                </li>
                            @endcan
                            @can('payment-purchase-report')
                                <li class="slide {{ menuActive('report.payment.purchase') }}">
                                    <a href="{{ route('report.payment.purchase') }}"
                                       class="side-menu__item {{ menuActive('report.payment.purchase') }}">{{ __('Payment Purchase') }}</a>
                                </li>
                            @endcan
                            @can('payment-sale-return-report')
                                <li class="slide {{ menuActive('report.payment.sale.return') }}">
                                    <a href="{{ route('report.payment.sale.return') }}"
                                       class="side-menu__item {{ menuActive('report.payment.sale.return') }}">{{ __('Payment Sale Return') }}</a>
                                </li>
                            @endcan
                            @can('payment-purchase-return-report')
                                <li class="slide {{ menuActive('report.payment.purchase.return') }}">
                                    <a href="{{ route('report.payment.purchase.return') }}"
                                       class="side-menu__item {{ menuActive('report.payment.purchase.return') }}">{{ __('Payment Purchase Return') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                <!-- End::slide -->
                @if (checkPermission(['language', 'setting']))
                    <li class="slide__category"><span class="category-name">{{ __('Site Settings') }}</span></li>
                    @can('list-language')
                        <li class="slide {{ menuActive('language*') }}">
                            <a href="{{ route('language.index') }}"
                               class="side-menu__item {{ menuActive('language*') }}">
                                <i class="side-menu__icon ri-global-fill"></i>
                                <span class="side-menu__label">{{ __('Language Settings') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('system-setting')
                        <li class="slide {{ menuActive('settings') }}">
                            <a href="{{ route('settings') }}" class="side-menu__item {{ menuActive('settings') }}">
                                <i class="side-menu__icon ri-settings-2-line"></i>
                                <span class="side-menu__label">{{ __('System Settings') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('mail-setting')
                        <li class="slide {{ menuActive('settings.email') }}">
                            <a href="{{ route('settings.email') }}"
                               class="side-menu__item {{ menuActive('settings.email') }}">
                                <i class="side-menu__icon ri-mail-settings-line"></i>
                                <span class="side-menu__label">{{ __('Mail Settings') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('email-template')
                        <li class="slide {{ menuActive('email-template*') }}">
                            <a href="{{ route('email-template.index') }}"
                               class="side-menu__item {{ menuActive('email-template*') }}">
                                <i class="side-menu__icon ri-mail-lock-line"></i>
                                <span class="side-menu__label">{{ __('Email Template') }}</span>
                            </a>
                        </li>
                    @endcan
                @endif
                <li class="slide__category"><span class="category-name">{{ __('Extra') }}</span></li>
                <li class="slide {{ menuActive('application') }}">
                    <a href="{{ route('application') }}"
                       class="side-menu__item {{ menuActive('application') }}">
                        <i class="side-menu__icon ri-apps-fill"></i>
                        <span class="side-menu__label">{{ __('Application') }}</span>
                    </a>
                </li>
                <li class="slide {{ menuActive('server') }}">
                    <a href="{{ route('server') }}"
                       class="side-menu__item {{ menuActive('server') }}">
                        <i class="side-menu__icon ri-server-fill"></i>
                        <span class="side-menu__label">{{ __('Server') }}</span>
                    </a>
                </li>
                <li class="slide {{ menuActive('clear') }}">
                    <a href="{{ route('clear') }}"
                       class="side-menu__item {{ menuActive('clear') }}">
                        <i class="side-menu__icon ri-copyleft-line"></i>
                        <span class="side-menu__label">{{ __('Cache') }}</span>
                    </a>
                </li>
            </ul>
            <div class="slide-right" id="slide-right">
                <i class="side-menu__icon ri-dashboard-line"></i>
            </div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
