<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Mercatavico</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('sources/data/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('sources/css/mercatavico.css') }}">
    @yield('late_head')
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>
