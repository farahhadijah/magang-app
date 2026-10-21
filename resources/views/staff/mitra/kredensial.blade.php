<x-app-layout>
    <x-slot name="title">
        Kelola Kredensial Mitra - SIBOLANG
    </x-slot>

    <div class="px-0 py-6 mx-auto max-w-4xl md:py-8" x-data="{ copied: false }">

        {{-- ================= NOTIFIKASI ================= --}}
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 bg-green-50 rounded-lg border-l-4 border-green-500 shadow-sm">
                <i class="mr-1 fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="p-4 mb-4 text-sm text-amber-800 bg-amber-50 rounded-lg border-l-4 border-amber-500 shadow-sm">
                <i class="mr-1 fa-solid fa-triangle-exclamation"></i>
                {{ session('warning') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 mb-4 text-sm text-red-800 bg-red-50 rounded-lg border-l-4 border-red-500 shadow-sm">
                <i class="mr-1 fa-solid fa-circle-xmark"></i>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 bg-red-50 rounded-lg border-l-4 border-red-500 shadow-sm">
                <ul class="text-sm list-disc list-inside text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= HEADER ================= --}}
        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-900 md:text-2xl">Kelola Kredensial Mitra</h1>
            <p class="mt-1 text-sm text-gray-500">
                Kirim ulang kredensial, perbarui email tujuan, atau reset password akun mitra.
            </p>
        </div>

        {{-- ================= INFO MITRA ================= --}}
        <div class="p-5 mb-6 bg-green-50 rounded-xl border border-green-200">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs font-medium text-gray-500">Nama Instansi</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ $mitra->tempatPkl->nama_tempat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Email Tujuan</p>
                    <p class="mt-1 font-semibold text-gray-800 break-all">
                        {{ $mitra->tempatPkl->email ?? '— Belum tersedia —' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Username</p>
                    <p class="mt-1 font-semibold text-gray-800 break-all">{{ $mitra->user->username ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Status Email Terakhir</p>
                    @if ($lastEmail)
                        @php $badge = $lastEmail->statusLabel(); @endphp
                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded {{ $badge['class'] }}">
                            {{ $badge['text'] }}
                        </span>
                        <span class="block mt-1 text-xs text-gray-500">
                            {{ $lastEmail->jenisLabel() }} · {{ $lastEmail->created_at->diffForHumans() }}
                        </span>
                    @else
                        <span
                            class="inline-block px-2 py-0.5 mt-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded">
                            Belum ada pengiriman
                        </span>
                    @endif
                </div>
            </div>

            {{-- ================= PASSWORD BARU (flash, sekali tampil) ================= --}}
            @if (session('password_baru'))
                @php
                    $pesanWA =
                        "Yth. {$mitra->tempatPkl->nama_tempat},\n\n" .
                        "Berikut kredensial akun Mitra PKL SIBOLANG Anda:\n\n" .
                        "Username: {$mitra->user->username}\n" .
                        'Password: ' .
                        session('password_baru') .
                        "\n\n" .
                        'Login: ' .
                        url('/login') .
                        "\n\n" .
                        "Segera ganti password setelah login pertama.\n\n" .
                        "Terima kasih,\nTim SIBOLANG UNISLA";
                @endphp

                <div class="p-4 mt-5 bg-white rounded-lg border-2 border-green-300" x-data="{ text: @js($pesanWA) }">
                    <div class="flex gap-3 items-start">
                        <div
                            class="flex flex-shrink-0 justify-center items-center w-9 h-9 text-green-700 bg-green-100 rounded-full">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-green-800">Password Baru Berhasil Dibuat</p>
                            <p class="mt-1 text-xs text-gray-600">
                                Password di bawah ini hanya ditampilkan sekali. Salin sekarang jika ingin dikirim manual
                                via WA.
                            </p>
                            <div class="grid grid-cols-1 gap-2 mt-3 sm:grid-cols-2">
                                <div class="p-2 bg-green-50 rounded border border-green-200">
                                    <p class="text-xs text-gray-500">Username</p>
                                    <p class="font-mono text-sm font-semibold text-gray-800 break-all">
                                        {{ $mitra->user->username }}</p>
                                </div>
                                <div class="p-2 bg-red-50 rounded border border-red-200">
                                    <p class="text-xs text-gray-500">Password Baru</p>
                                    <p class="font-mono text-sm font-bold text-red-700 break-all">
                                        {{ session('password_baru') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <button type="button"
                                    @click="navigator.clipboard.writeText(text); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                    <i class="mr-1 fa-solid fa-copy"></i>
                                    <span x-show="!copied">Salin Template WA</span>
                                    <span x-show="copied" x-cloak><i class="mr-1 fa-solid fa-check"></i>Tersalin!</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ================= AKSI 1: KIRIM ULANG ================= --}}
        <div class="p-5 mb-4 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="flex gap-4 justify-between items-start">
                <div>
                    <h2 class="font-semibold text-gray-800">1. Kirim Ulang Kredensial</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Generate password baru & kirim ke email instansi saat ini.
                        Password lama akan invalid.
                    </p>
                </div>
                <form method="POST" action="{{ route('staff.mitra.kredensial.resend', $mitra->id) }}"
                    onsubmit="return confirm('Password akan digenerate ulang dan dikirim ke email instansi. Password lama tidak akan berlaku lagi. Lanjutkan?')">
                    @csrf
                    <button type="submit" @disabled(empty($mitra->tempatPkl->email))
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="mr-1 fa-solid fa-paper-plane"></i> Kirim Ulang
                    </button>
                </form>
            </div>
        </div>

        {{-- ================= AKSI 2: EDIT EMAIL LALU KIRIM ================= --}}
        <div class="p-5 mb-4 bg-white rounded-xl border border-gray-200 shadow-sm">
            <h2 class="font-semibold text-gray-800">2. Edit Email & Kirim Ulang</h2>
            <p class="mt-1 mb-3 text-sm text-gray-500">
                Perbarui email tujuan instansi, generate password baru, lalu kirim.
            </p>
            <form method="POST" action="{{ route('staff.mitra.kredensial.update-email', $mitra->id) }}"
                onsubmit="return confirm('Email akan diperbarui dan kredensial dikirim ke alamat baru. Lanjutkan?')">
                @csrf
                <div class="flex flex-col gap-2 sm:flex-row">
                    <input type="email" name="email" required value="{{ old('email', $mitra->tempatPkl->email) }}"
                        placeholder="email@instansi.ac.id"
                        class="flex-1 px-3 py-2 text-sm rounded-lg border focus:ring-2 focus:ring-green-500">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg transition hover:bg-blue-700">
                        <i class="mr-1 fa-solid fa-envelope"></i> Simpan & Kirim
                    </button>
                </div>
            </form>
        </div>

        {{-- ================= AKSI 3: RESET PASSWORD LALU KIRIM ================= --}}
        <div class="p-5 mb-6 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="flex gap-4 justify-between items-start">
                <div>
                    <h2 class="font-semibold text-gray-800">3. Reset Password & Kirim Ulang</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Generate password baru tanpa ubah email, lalu kirim ulang.
                    </p>
                </div>
                <form method="POST" action="{{ route('staff.mitra.kredensial.reset-password', $mitra->id) }}"
                    onsubmit="return confirm('Password akan direset dan dikirim ulang. Lanjutkan?')">
                    @csrf
                    <button type="submit" @disabled(empty($mitra->tempatPkl->email))
                        class="px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg transition hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="mr-1 fa-solid fa-key"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>

        {{-- ================= RIWAYAT EMAIL ================= --}}
        <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-5 py-3 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Riwayat Pengiriman Email</h2>
                <p class="text-xs text-gray-500">10 pengiriman terakhir untuk mitra ini.</p>
            </div>

            @if ($emailLogs->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 font-semibold text-left text-gray-600">Waktu</th>
                                <th class="px-4 py-2 font-semibold text-left text-gray-600">Jenis</th>
                                <th class="px-4 py-2 font-semibold text-left text-gray-600">Email Tujuan</th>
                                <th class="px-4 py-2 font-semibold text-left text-gray-600">Status</th>
                                <th class="px-4 py-2 font-semibold text-left text-gray-600">Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($emailLogs as $log)
                                @php $badge = $log->statusLabel(); @endphp
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $log->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-2">{{ $log->jenisLabel() }}</td>
                                    <td class="px-4 py-2 break-all">{{ $log->email_tujuan }}</td>
                                    <td class="px-4 py-2">
                                        <span
                                            class="inline-block px-2 py-0.5 text-xs font-semibold rounded {{ $badge['class'] }}">
                                            {{ $badge['text'] }}
                                        </span>
                                        @if ($log->error_message)
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ \Illuminate\Support\Str::limit($log->error_message, 80) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-xs text-gray-600">
                                        {{ $log->triggeredBy?->getNama() ?? 'Sistem' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-sm text-center text-gray-500">
                    <i class="mb-2 text-2xl text-gray-300 fa-solid fa-inbox"></i>
                    <p>Belum ada riwayat pengiriman email.</p>
                </div>
            @endif
        </div>

        {{-- ================= BACK ================= --}}
        <div class="flex justify-end mt-6">
            <a href="{{ route('staff.manajemen-mitra.show', $mitra->id) }}"
                class="inline-flex gap-2 items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 hover:bg-gray-50">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Mitra
            </a>
        </div>
    </div>
</x-app-layout>
