<x-app-layout>

<x-slot name="title">
Detail Mitra - MagangApp
</x-slot>

<div class="px-4 py-6 md:px-6">

<h1 class="mb-6 text-xl font-bold md:text-2xl text-slate-900">
Detail Mitra
</h1>

<!-- INFO MITRA -->
<div class="p-4 mb-6 rounded-lg shadow bg-green-50 text-slate-800">

    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
        <p><b>Tempat PKL :</b> {{ $mitra->nama_tempat }}</p>
        <p><b>No HP Mitra :</b> {{ $mitra->no_hp ?? '-' }}</p>
    </div>

    @if(session('username'))
    <div class="p-4 mt-4 border border-green-300 rounded bg-green-50">
        <p class="font-semibold text-green-700">
            Akun Mitra Berhasil Dibuat Ulang
        </p>

        <p class="mt-2">
            <b>Username :</b> {{ session('username') }}
        </p>

        <p>
            <b>Password :</b> {{ session('password') }}
        </p>
    </div>
    @endif

</div>


<!-- HEADER + AKSI -->
<div class="flex flex-col gap-3 mb-4 md:flex-row md:items-center md:justify-between">

    <h2 class="text-lg font-semibold md:text-xl">
        Daftar Mahasiswa PKL ({{ $mahasiswa->total() }})
    </h2>

    <div class="flex flex-col gap-2 sm:flex-row">

        <form action="{{ route('staff.mitra.regenerate', $mitra->id) }}" method="POST">
            @csrf
            <button class="w-full px-4 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                Generate Ulang Akun
            </button>
        </form>

        @if(session('username'))
        <button onclick="kirimWA()" class="w-full px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
            Kirim ke WhatsApp
        </button>
        @endif

    </div>

</div>


<!-- DESKTOP TABLE -->
<div class="hidden md:block overflow-x-auto bg-white rounded-lg shadow">
    <table class="w-full">

        <thead class="bg-green-100 border border-green-200 text-slate-800">
            <tr>
                <th class="p-3 text-left">NIM</th>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Angkatan</th>
                <th class="p-3 text-left">No HP</th>
            </tr>
        </thead>

        <tbody>
        @forelse($mahasiswa as $mhs)
            <tr class="border-t">
                <td class="p-3">{{ $mhs->nim }}</td>
                <td class="p-3">{{ $mhs->nama }}</td>
                <td class="p-3">{{ $mhs->angkatan }}</td>
                <td class="p-3 nomor">{{ $mhs->no_hp }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="p-4 text-center text-gray-500">
                    Belum ada mahasiswa PKL di tempat ini
                </td>
            </tr>
        @endforelse
        </tbody>

    </table>
</div>


<!-- MOBILE CARD -->
<div class="space-y-4 md:hidden">

    @forelse($mahasiswa as $mhs)
        <div class="p-4 bg-white border rounded-lg shadow-sm">

            <div class="mb-2">
                <p class="text-sm text-gray-500">NIM</p>
                <p class="font-medium">{{ $mhs->nim }}</p>
            </div>

            <div class="mb-2">
                <p class="text-sm text-gray-500">Nama</p>
                <p>{{ $mhs->nama }}</p>
            </div>

            <div class="mb-2">
                <p class="text-sm text-gray-500">Angkatan</p>
                <p>{{ $mhs->angkatan }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">No HP</p>
                <p class="nomor">{{ $mhs->no_hp }}</p>
            </div>

        </div>
    @empty
        <div class="p-4 text-center text-gray-500 bg-white border rounded">
            Belum ada mahasiswa PKL di tempat ini
        </div>
    @endforelse

</div>


<!-- PAGINATION -->
<div class="mt-4 flex justify-center">
    {{ $mahasiswa->links() }}
</div>

</div>


<script>

function kirimWA(){

let nomor = []

document.querySelectorAll('.nomor').forEach(function(el){

let no = el.innerText.trim()

if(no !== ""){

if(no.startsWith("0")){
no = "62" + no.substring(1)
}

nomor.push(no)

}

})

if(nomor.length === 0){
alert("Tidak ada nomor mahasiswa")
return
}

let username = "{{ session('username') }}"
let password = "{{ session('password') }}"

let pesan = encodeURIComponent(
"Halo, berikut akun login mitra PKL.\n\nUsername: "+username+"\nPassword: "+password
)

nomor.forEach(function(no){
window.open("https://wa.me/" + no + "?text=" + pesan, "_blank")
})

}

</script>

</x-app-layout>