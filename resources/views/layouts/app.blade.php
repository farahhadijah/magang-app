<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ $title ?? config('app.name', 'MagangApp') }}
    </title>
    <link rel="icon" type="image/png" href="{{ asset('img/logounisla.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

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
            class="fixed inset-y-0 left-0 z-40 w-64 transition-transform duration-300 transform bg-primary md:translate-x-0 md:static md:inset-0"
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
{{-- script --}}
    <script>
    function submitReview(event, id) {
    event.preventDefault();

    const status = document.getElementById('status-select-' + id).value;
    const catatan = document.getElementById('catatan-' + id).value;
    const token = document.querySelector('#modal-' + id + ' input[name=_token]').value;

    fetch("/dosen/logbook/" + id + "/review-ajax", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({status: status, catatan: catatan})
    })
    .then(res => {
        if(!res.ok) throw new Error('Network response not ok');
        return res.json();
    })
    .then(data => {
        if(data.success) {
            let statusCell = document.getElementById('status-' + id);
            let html = '';
            if(data.status === 'approved') {
                html = '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Disetujui</span>';
            } else if(data.status === 'pending') {
                html = '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Perlu Revisi</span>';
            }
            statusCell.innerHTML = html;
            closeModal(id);
        }
    })
    .catch(err => console.error(err));
}

function openModal(id) {
    document.getElementById('modal-' + id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById('modal-' + id).classList.add('hidden');
}

    </script>


</body>



</html>
