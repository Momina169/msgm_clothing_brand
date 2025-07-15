<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MSGM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preload" as="style" href="{{ asset('build/assets/app-e802707b.css') }}" />
    <link rel="stylesheet" href="{{asset('/css_bootstrap.min.css')}}">
    <link rel="manifest" href="{{ asset('build/manifest.json') }}">
    <script rel="jquery" src="{{ asset('/jquery_3.6.4_jquery.min.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <!-- Secure Links -->
    <link rel="preload" as="style" href="{{ asset('build/assets/app-e802707b.css') }}" />
    <link rel="modulepreload" href="{{ asset('build/assets/app-4a08c204.js') }}" />
    <link rel="stylesheet" href="{{ asset('build/assets/app-e802707b.css') }}" />
    <script type="module" src="{{ asset('build/assets/app-4a08c204.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css_bootstrap.min.css') }}">

</head>

<body class="font-sans text-gray-900 antialiased bg-light">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 ">
        <div>
            <a href="{{route('dashboard')}}">
                <img src="{{asset('images/msgm-logo.png')}}" width="350px" height="auto">

            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white  shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>