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
{{-- script kedepannya akan dipindah sementara dibiarkan disini dulu--}}
    <script>
    function submitReview(event, id) {
    event.preventDefault();

    const status = document.getElementById('status-select-' + id).value;
    const catatanEl = document.getElementById('catatan-' + id);
    const catatan = catatanEl ? catatanEl.value : '';
    const token = document.querySelector('#modal-' + id + ' input[name=_token]').value;

    // clear previous validation UI
    if(catatanEl) {
        const errElOld = document.getElementById('catatan-error-' + id);
        if(errElOld) { errElOld.classList.add('hidden'); errElOld.textContent = ''; }
        catatanEl.classList.remove('border-red-500');
    }

    // If the lecturer chose "revisi", require a catatan (quick client check)
    if(status === 'revisi' && (!catatan || catatan.trim() === '')) {
        // simple UI feedback
        if(catatanEl) {
            catatanEl.classList.add('border-red-500');
            catatanEl.focus();
        }
        alert('Silakan isi catatan ketika memilih "Perlu Revisi".');
        return;
    }

    // Build payload: include catatan only when needed
    const payload = { status: status };
    if(status === 'revisi') payload.catatan = catatan;

    fetch("/dosen/logbook/" + id + "/review-ajax", {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify(payload)
    })
    .then(res => {
        // handle validation errors
        if(res.status === 422) {
            return res.json().then(json => {
                if(json.errors) {
                    // show catatan error if present
                    if(json.errors.catatan) {
                        const errEl = document.getElementById('catatan-error-' + id);
                        if(errEl) {
                            errEl.textContent = json.errors.catatan.join(' ');
                            errEl.classList.remove('hidden');
                        }
                        if(catatanEl) {
                            catatanEl.classList.add('border-red-500');
                            catatanEl.focus();
                        }
                    }
                }
                // stop further processing
                throw new Error('validation');
            });
        }

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
    .catch(err => {
        if(err && err.message === 'validation') {
            // validation errors already shown
            return;
        }
        console.error(err);
        alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
    });
}

// Toggle visibility/required state of the catatan field based on status select
function toggleCatatan(id) {
    const select = document.getElementById('status-select-' + id);
    const wrapper = document.getElementById('catatan-wrapper-' + id);
    const textarea = document.getElementById('catatan-' + id);
    const errEl = document.getElementById('catatan-error-' + id);
    if(!select || !wrapper || !textarea) return;

    if(select.value === 'revisi') {
        wrapper.classList.remove('hidden');
        textarea.removeAttribute('disabled');
        // mark visually required (we'll still validate on submit)
        textarea.classList.remove('opacity-50');
        // clear previous error when showing
        if(errEl) { errEl.classList.add('hidden'); errEl.textContent = ''; }
        textarea.classList.remove('border-red-500');
    } else {
        // hide/disable the catatan field for non-revision statuses
        wrapper.classList.add('hidden');
        textarea.setAttribute('disabled', 'disabled');
        textarea.classList.add('opacity-50');
        // clear/disable any previous validation error
        if(errEl) { errEl.classList.add('hidden'); errEl.textContent = ''; }
        textarea.classList.remove('border-red-500');
    }
}

function openModal(id) {
    document.getElementById('modal-' + id).classList.remove('hidden');
    // ensure catatan visibility matches current select value
    if (typeof toggleCatatan === 'function') toggleCatatan(id);
}

function closeModal(id) {
    document.getElementById('modal-' + id).classList.add('hidden');
}

// ======================menangani refresh halaman============
document.addEventListener("DOMContentLoaded", function () {
        if (localStorage.getItem("scrollPosition")) {
            window.scrollTo(0, localStorage.getItem("scrollPosition"));
            localStorage.removeItem("scrollPosition");
        }

        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", function () {
                localStorage.setItem("scrollPosition", window.scrollY);
            });
        });
    });

    </script>


</body>



</html>
