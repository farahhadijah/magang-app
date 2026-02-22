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
function openModal(id) {
    const modal = document.getElementById('modal-' + id);
    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Sinkronkan tampilan catatan saat pertama buka
    toggleCatatan(id);
}

function closeModal(id) {
    const modal = document.getElementById('modal-' + id);
    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function toggleCatatan(id) {
    const select = document.getElementById('status-select-' + id);
    const wrapper = document.getElementById('catatan-wrapper-' + id);
    const textarea = document.getElementById('catatan-' + id);

    if (!select || !wrapper || !textarea) return;

    if (select.value === 'revisi') {
        wrapper.classList.remove('hidden');
        textarea.removeAttribute('disabled');
        textarea.classList.remove('opacity-50');
    } else {
        wrapper.classList.add('hidden');
        textarea.setAttribute('disabled', 'disabled');
        textarea.classList.add('opacity-50');
        textarea.classList.remove('border-red-500');
    }
}

function submitReview(event, id) {
    event.preventDefault();

    const select = document.getElementById('status-select-' + id);
    const textarea = document.getElementById('catatan-' + id);
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');

    if (!select || !tokenMeta) {
        console.error("Element tidak ditemukan");
        return;
    }

    const status = select.value;
    let catatan = textarea ? textarea.value.trim() : "";

    const token = tokenMeta.getAttribute('content');

    // Reset error style
    if (textarea) {
        textarea.classList.remove('border-red-500');
    }

    // Validasi client-side
    if (status === "revisi" && catatan === "") {
        alert('Silakan isi catatan ketika memilih "Perlu Revisi".');
        if (textarea) {
            textarea.classList.add('border-red-500');
            textarea.focus();
        }
        return;
    }

    // 🔥 Penting: jika approved, jangan kirim catatan
    if (status === "approved") {
        catatan = null;
    }

    fetch("/dosen/logbook/" + id + "/review-ajax", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
            "Accept": "application/json"
        },
        body: JSON.stringify({
            status: status,
            catatan: catatan
        })
    })
    .then(res => {
        if (res.status === 422) {
            return res.json().then(data => {
                if (data.errors?.catatan) {
                    alert(data.errors.catatan.join(" "));
                }
                throw new Error("validation");
            });
        }

        if (!res.ok) {
            return res.json().then(data => {
                alert(data.message || "Terjadi kesalahan.");
                throw new Error("server");
            });
        }

        return res.json();
    })
    .then(data => {
        if (!data.success) return;

        const statusCell = document.getElementById('status-' + id);
        if (statusCell) {
            if (data.status === "approved") {
                statusCell.innerHTML =
                    '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Disetujui</span>';
            } else if (data.status === "revisi") {
                statusCell.innerHTML =
                    '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Perlu Revisi</span>';
            }
        }

        closeModal(id);
    })
    .catch(err => {
        if (err.message === "validation") return;
        console.error(err);
        alert("Terjadi kesalahan saat mengirim data. Silakan coba lagi.");
    });
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
