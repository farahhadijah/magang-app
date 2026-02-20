<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-green-800">
            Akun Mitra Berhasil Dibuat
        </h2>
    </x-slot>

    <div class="max-w-3xl px-4 py-6 mx-auto">

        <div class="p-6 bg-white rounded shadow">

            <h3 class="mb-4 text-lg font-semibold">
                Detail Akun
            </h3>

            <p><strong>Username:</strong> {{ session('username') }}</p>
            <p><strong>Password:</strong> {{ session('password') }}</p>
            <p><strong>Nomor HP:</strong> {{ $tempat->no_hp }}</p>

            <hr class="my-6">

            @php
                $pesan = "Yth. {$tempat->nama_tempat},\n\n"
                    . "Berikut akun login Mitra PKL:\n"
                    . "Username: " . session('username') . "\n"
                    . "Password: " . session('password') . "\n\n"
                    . "Silakan login di:\n"
                    . url('/login') . "\n\n"
                    . "Dimohon segera mengganti password setelah login pertama.\n\n"
                    . "Terima kasih.";
            @endphp

            <h4 class="mb-2 font-semibold">Template Pesan WhatsApp</h4>

            <textarea class="w-full p-3 border rounded" rows="8" readonly>
{{ $pesan }}
            </textarea>

            <div class="flex gap-4 mt-4">

                {{-- Tombol WhatsApp --}}
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $tempat->no_hp) }}?text={{ urlencode($pesan) }}"
                   target="_blank"
                   class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                    Kirim ke WhatsApp
                </a>

                {{-- Tombol Kembali --}}
                <a href="{{ route('staff.mitra.index') }}"
                   class="px-4 py-2 text-white bg-gray-600 rounded hover:bg-gray-700">
                    Kembali
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
