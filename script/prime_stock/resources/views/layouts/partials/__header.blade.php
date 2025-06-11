<header class="app-header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="{{ route('dashboard') }}" class="header-logo">
                        <img src="{{ asset('assets/uploads') }}/{{ settings('logo') }}" alt="logo" class="desktop-logo">
                        <img src="{{ asset('assets/uploads') }}/{{ settings('favicon') }}" alt="logo" class="toggle-logo">
                        <img src="{{ asset('assets/uploads') }}/{{ settings('logo') }}" alt="logo" class="desktop-white">
                        <img src="{{ asset('assets/uploads') }}/{{ settings('favicon') }}" alt="logo" class="toggle-white">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a aria-label="Hide Sidebar"
                   class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                   data-bs-toggle="sidebar" href="javascript:void(0);">
                    <i class="header-icon fe fe-align-left"></i>
                </a>
                

                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <div class="header-content-right">

            <div class="header-element Search-element d-block d-lg-none">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside"
                   data-bs-toggle="dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="header-link-icon">
                        <path
                            d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/>
                    </svg>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu dropdown-menu-end Search-element-dropdown"
                    data-popper-placement="none">
                    <li>
                        <div class="input-group w-100 p-2">
                            <input type="text" class="form-control" placeholder="Search....">
                            <div class="btn btn-primary">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Start::header-element -->
            <div class="header-element country-selector">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside"
                   data-bs-toggle="dropdown">
                    <img src="https://unpkg.com/language-icons/icons/{{ config('app.locale') }}.svg" alt="img"
                         class="avatar avatar-xs lh-1 me-2">
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu dropdown-menu-end country-dropdown"
                    data-popper-placement="none">
                    @foreach(\App\Models\Language::where('status',true)->get() as $lang)
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('language-update',['name'=> $lang->locale]) }}">
                                    <span>
                                        <img src="https://unpkg.com/language-icons/icons/{{ $lang->locale }}.svg" alt="img"
                                             class="avatar avatar-xs lh-1 me-2">
                                    </span>
                            {{$lang->name}}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element header-fullscreen">
                <!-- Start::header-link -->
                <a onclick="openFullscreen();" href="javascript:void(0);" class="header-link">
                    <svg xmlns="http://www.w3.org/2000/svg" class="full-screen-open full-screen-icon header-link-icon"
                         height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor">
                        <path d="M0 0h24v24H0V0z" fill="none"/>
                        <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="full-screen-close full-screen-icon header-link-icon d-none" fill="currentColor"
                         height="24" viewBox="0 -960 960 960" width="24">
                        <path
                            d="M320-200v-120H200v-80h200v200h-80Zm240 0v-200h200v80H640v120h-80ZM200-560v-80h120v-120h80v200H200Zm360 0v-200h80v120h120v80H560Z"/>
                    </svg>
                </a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element headerProfile-dropdown">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile"
                   data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <img src="{{ asset('assets/images/user.png') }}" alt="img" width="37" height="37"
                         class="rounded-circle">
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu pt-0 header-profile-dropdown dropdown-menu-end main-profile-menu"
                    aria-labelledby="mainHeaderProfile">
                    <li>
                        <div class="main-header-profile bg-primary menu-header-content text-fixed-white">
                            <div class="my-auto">
                                <h6 class="mb-0 lh-1 text-fixed-white">{{ auth()->user()->name }}</h6><span
                                    class="fs-11 op-7 lh-1">
                                    {{ auth()->user()->getRoleNames()->first() }}
                                </span>
                            </div>
                        </div>
                    </li>
                    <li><a class="dropdown-item d-flex" href="{{ route('profile.edit') }}"><i
                                class="bx bx-user-circle fs-18 me-2 op-7"></i>{{ __('Profile') }}</a></li>
                    <li><a class="dropdown-item d-flex" href="{{ route('settings') }}"><i
                                class="bx bx-cog fs-18 me-2 op-7"></i>{{ __('Settings') }}</a></li>
                    <li><a class="dropdown-item d-flex" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit()"><i
                                class="bx bx-log-out fs-18 me-2 op-7"></i>{{ __('Sign Out') }}</a></li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                    </form>
                </ul>
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->
    <!-- Search Card -->
    <div class="card custom-card search-card d-none">
        <div class="card-body p-2">

        </div>
    </div>
    <!-- Search Card -->

</header>
