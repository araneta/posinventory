<!doctype html>
<html lang="en">

<head>
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,900" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/uploads') }}/{{ settings('favicon') }}">

    <link rel="stylesheet" href="{{ asset('assets/error/css/style.css') }}">

</head>

<body>
    <div id="notfound">
		@yield('content')
	</div>
</body>

</html>
