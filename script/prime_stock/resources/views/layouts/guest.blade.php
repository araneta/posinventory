<!doctype html>
<html lang="en">

<head>
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/uploads') }}/{{ settings('favicon') }}">

    <link rel="stylesheet" href="{{ asset('assets/login/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/login/css/style.css') }}">

</head>

<body class="my-login-page">
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper">
                    <div class="brand">
                        <img src="{{ asset('assets/uploads') }}/{{ settings('favicon') }}" alt="logo">
                    </div>
                    <div class="card fat">
                        @yield('content')
                    </div>
                    <div class="footer">
                        {{ settings('footer_text') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/login/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/login/js/popper.js') }}"></script>
    <script src="{{ asset('assets/login/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/login/js/main.js') }}"></script>
    @stack('script')
</body>

</html>
