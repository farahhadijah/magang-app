<nav
    class="relative z-[999] flex flex-col w-64 h-screen text-green-100"
>

    <!-- ================= LOGO ================= -->
    <div class="flex items-center justify-center h-16 border-b border-green-800 shrink-0">

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 font-semibold text-white">

            <x-application-logo class="w-auto h-8" />

            MagangApp

        </a>

    </div>


    <!-- ================= MENU ================= -->
    <div class="flex-1 px-3 py-4 space-y-1 overflow-y-auto sidebar-scroll">

        <!-- Dashboard -->
        <a
            href="{{ route('dashboard') }}"
            @click="$dispatch('close-sidebar')"
            class="
                flex items-center gap-2 px-4 py-2 rounded-lg
                hover:bg-green-800 transition
                {{ request()->routeIs('dashboard') ? 'bg-green-800 text-amber-300' : '' }}
            "
        >
            <i class="w-5 fa-solid fa-chart-line"></i>
            Dashboard
        </a>

        <!-- Profile -->
        <a
            href="{{ route('profile.edit') }}"
            @click="$dispatch('close-sidebar')"
            class="
                flex items-center gap-2 px-4 py-2 rounded-lg
                hover:bg-green-800 transition
                {{ request()->routeIs('profile.*') ? 'bg-green-800 text-amber-300' : '' }}
            "
        >
            <i class="w-5 fa-solid fa-user"></i>
            Profile
        </a>

        {{-- ================= ROLE MENU ================= --}}

        @if(auth()->user()->role === 'mahasiswa')
            @include('layouts.navbar.mahasiswa')

        @elseif(auth()->user()->role === 'dosen')

            {{-- NAVBAR DOSEN --}}
            @include('layouts.navbar.dosen')

            {{-- TAMBAHAN NAVBAR KAPRODI --}}
            @if(auth()->user()->isKaprodi())
                <div class="px-4 pt-4 mt-6 text-xs text-gray-300 uppercase border-t border-green-700">
                    Menu Kaprodi
                </div>

                @include('layouts.navbar.kaprodi')
            @endif

        @elseif(auth()->user()->role === 'admin')
            @include('layouts.navbar.admin')

        @elseif(auth()->user()->role === 'staff_tu')
            @include('layouts.navbar.staff')

        @elseif(auth()->user()->role === 'mitra')
            @include('layouts.navbar.mitra')

        @endif

    </div>


    <!-- ================= USER ================= -->
    <div class="p-4 border-t border-green-800 shrink-0">

        <div class="text-sm font-medium text-white truncate">
            {{ auth()->user()->getNama() }}
        </div>

        <div class="mb-3 text-xs text-green-300 truncate">
            {{ auth()->user()->email }}
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="w-full px-3 py-2 text-left transition bg-green-800 rounded-md hover:bg-amber-500 hover:text-green-900"
            >
                <i class="mr-1 fa-solid fa-right-from-bracket"></i>
                Logout
            </button>

        </form>

    </div>

</nav>