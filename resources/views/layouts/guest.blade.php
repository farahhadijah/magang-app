<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'MagangApp') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="icon" type="image/png" href="{{ asset('img/logounisla.png') }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased text-gray-900 bg-green-50">

    <div class="flex flex-col items-center px-4 pt-6 min-h-screen sm:justify-center sm:pt-0">

        {{-- Logo --}}
        @if (!isset($hideGuestHeader) || !$hideGuestHeader)
            <div class="flex justify-center mb-2">
                <a href="/">
                    <x-application-logo class="w-24 h-24 text-green-600 fill-current" />
                </a>
            </div>
        @endif

        {{-- Content --}}
        <div class="w-full">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @if (!isset($hideGuestFooter) || !$hideGuestFooter)
            <div class="mt-2 text-sm text-center text-green-700">
                &copy; 2026 SIBOLANG.
                Developed by Nur Faizah | Farah Hadijah.
                All rights reserved.
            </div>
        @endif

    </div>

</body>

</html>
