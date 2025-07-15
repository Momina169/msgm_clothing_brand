


<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/275c3c76a3.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css_bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicons -->
    <link href="{{ asset('images/logo.png') }}" rel="icon">

     <link rel="preload" as="style" href="{{ asset('build/assets/app-e802707b.css') }}" />
    <link rel="modulepreload" href="{{ asset('build/assets/app-4a08c204.js') }}" /> 
    <style>
        .heading-font{
             font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    }
    </style>
</head>

<body>
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-Gn5384xq1aoWXA+058RXPacRY63CFtze/JIMnXaA9I=" crossorigin="anonymous"></script>

    <!-- Include Popper.js and Bootstrap JS from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>

    <script src="{{ asset('sidebars.js') }}"></script>

</html>
