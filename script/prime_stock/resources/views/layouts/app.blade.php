<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-nav-layout="vertical"
      data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">

@include('layouts.partials.__head')

<body>

<!-- Loader -->
<div id="loader">
    <img src="{{ asset('assets/images/loader.svg') }}" alt="">
</div>
<!-- Loader -->

<div class="page">
    <!-- app-header -->
    @include('layouts.partials.__header')
    <!-- /app-header -->

    <!--End modal -->
    <!-- Start::app-sidebar -->
    @include('layouts.partials.__sidebar')
    <!-- End::app-sidebar -->

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">

            @yield('content')

        </div>
    </div>
    <!-- End::app-content -->

    <!-- Footer Start -->
    @include('layouts.partials.__footer')
    <!-- Footer End -->

</div>


{{--Floating Action Button--}}
<div class="floatingButtonWrap">
    <div class="floatingButtonInner">
        <a href="#" class="floatingButton">
            <i class="fa fa-plus icon-default"></i>
        </a>
        <ul class="floatingMenu">
            <li>
                <a href="{{ route('supplier.create') }}">{{ __('Add Supplier') }}</a>
            </li>
            <li>
                <a href="{{ route('customer.create') }}">{{ __('Add Customer') }}</a>
            </li>
            <li>
                <a href="{{ route('purchase.create') }}">{{ __('Add Purchase') }}</a>
            </li>
            <li>
                <a href="{{ route('sale.create') }}">{{ __('Add Sale') }}</a>
            </li>
            <li>
                <a href="{{ route('report.stock') }}">{{ __('Stock Report') }}</a>
            </li>
            <li>
                <a href="{{ route('report.profit.loss') }}">{{ __('Profit Loss Report') }}</a>
            </li>
            <li>
                <a href="{{ route('report.sale') }}">{{ __('Sale Report') }}</a>
            </li>
            <li>
                <a href="{{ route('report.purchase') }}">{{ __('Purchase Report') }}</a>
            </li>
        </ul>
    </div>
</div>

<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow"><i class="las la-angle-double-up"></i></span>
</div>
<div id="responsive-overlay"></div>
<!-- Scroll To Top -->

@include('layouts.partials.__script')

</body>

</html>
