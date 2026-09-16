<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Manifest -->
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logounisla.png') }}">

    <title>{{ $title ?? config('app.name', 'MagangApp') }}</title>

    <!-- Favicon standard -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logounisla.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/logounisla.png') }}">

    <!-- WAJIB untuk mobile -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logounisla.png') }}">

    <!-- ========== FONT AWESOME (PERTAHANKAN) ========== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- ========== BOOTSTRAP ICONS (TAMBAHAN) ========== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Map Preview -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Scripts -->
    <style>
        [x-cloak] {
            display: none !important;
        }
        
        /* Fallback jika Font Awesome tidak load */
        .fa-solid, .fa-regular, .fa-brands {
            font-family: 'Font Awesome 6 Free', 'FontAwesome', sans-serif;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="relative font-sans antialiased">

<div
    x-data="{ sidebarOpen: false }"
    @close-sidebar.window="sidebarOpen = false"
    class="min-h-screen bg-green-50"
>

    <!-- ================= MOBILE TOPBAR ================= -->
    <div class="flex items-center justify-between px-4 text-white md:hidden bg-primary h-14">

        <!-- Hamburger -->
        <button @click="sidebarOpen = true">
            <i class="text-xl fa-solid fa-bars"></i>
        </button>

        <!-- User Name -->
        <div class="font-medium">
            {{ auth()->user()->nama ?? auth()->user()->name }}
        </div>

    </div>

    <div class="flex">

        <!-- ================= SIDEBAR ================= -->
        <div
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed z-[9999] inset-y-0 left-0 w-64 transition-transform duration-300 transform bg-primary md:translate-x-0 md:static md:inset-0"
        >
            @include('layouts.navigation')
        </div>

        <!-- ================= OVERLAY MOBILE ================= -->
        <div
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-black bg-opacity-40 md:hidden"
        ></div>

        <!-- ================= CONTENT ================= -->
        <main
            class="flex-1 h-screen p-4 overflow-y-auto md:p-6"
            @click="sidebarOpen = false"
        >
            {{ $slot }}
        </main>

    </div>

</div>

@stack('scripts')
<!-- Map Preview -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- ========== FIX UNTUK FONT AWESOME ========== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah Font Awesome ter-load
        var faLoaded = false;
        var links = document.querySelectorAll('link[href*="font-awesome"]');
        links.forEach(function(link) {
            if (link.sheet) {
                faLoaded = true;
            }
        });
        
        // Jika tidak ter-load, gunakan Bootstrap Icons sebagai fallback
        if (!faLoaded) {
            console.warn('Font Awesome tidak ter-load, gunakan Bootstrap Icons');
            // Ubah semua icon fa-solid ke bi
            document.querySelectorAll('.fa-solid, .fa-regular, .fa-brands').forEach(function(el) {
                var classes = el.className.split(' ');
                classes.forEach(function(cls) {
                    if (cls.startsWith('fa-')) {
                        // Mapping icon
                        var iconMap = {
                            'fa-bars': 'bi-list',
                            'fa-building': 'bi-building',
                            'fa-check-circle': 'bi-check-circle',
                            'fa-clock': 'bi-clock',
                            'fa-file-pdf': 'bi-file-pdf',
                            'fa-upload': 'bi-upload',
                            'fa-download': 'bi-download',
                            'fa-paper-plane': 'bi-send',
                            'fa-map': 'bi-map',
                            'fa-map-location-dot': 'bi-geo-alt',
                            'fa-info-circle': 'bi-info-circle',
                            'fa-triangle-exclamation': 'bi-exclamation-triangle',
                            'fa-circle-exclamation': 'bi-exclamation-circle',
                            'fa-circle-xmark': 'bi-x-circle',
                            'fa-envelope-open-text': 'bi-envelope-paper',
                            'fa-arrow-right': 'bi-arrow-right',
                            'fa-arrow-left': 'bi-arrow-left',
                            'fa-rotate-right': 'bi-arrow-repeat',
                            'fa-user-graduate': 'bi-person-graduate',
                            'fa-check-double': 'bi-check-all',
                            'fa-flag-checkered': 'bi-flag',
                            'fa-timeline': 'bi-clock-history',
                            'fa-file-lines': 'bi-file-text',
                            'fa-folder-open': 'bi-folder-open',
                            'fa-receipt': 'bi-receipt',
                            'fa-ticket': 'bi-ticket',
                            'fa-message': 'bi-chat',
                            'fa-star': 'bi-star',
                            'fa-clipboard-check': 'bi-clipboard-check',
                            'fa-book': 'bi-book',
                            'fa-list-check': 'bi-list-check',
                            'fa-file-earmark-plus': 'bi-file-earmark-plus',
                            'fa-file-circle-plus': 'bi-file-earmark-plus',
                            'fa-server': 'bi-server',
                        };
                        
                        // Cari mapping
                        var newClass = iconMap[cls] || 'bi-' + cls.replace('fa-', '');
                        el.classList.remove(cls);
                        el.classList.add(newClass);
                    }
                });
                // Ubah class utama
                if (el.classList.contains('fa-solid')) {
                    el.classList.remove('fa-solid');
                }
                if (el.classList.contains('fa-regular')) {
                    el.classList.remove('fa-regular');
                }
                if (el.classList.contains('fa-brands')) {
                    el.classList.remove('fa-brands');
                }
            });
        }
    });
</script>

</body>
</html>