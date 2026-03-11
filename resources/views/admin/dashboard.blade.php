<x-app-layout>
<x-slot name="title">
Admin - MagangApp
</x-slot>

<div class="px-4 py-6 mx-auto space-y-6 max-w-7xl">

<h2 class="text-xl font-semibold text-gray-800">
Dashboard Admin
</h2>

{{-- STATISTIK --}}
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

<div class="p-4 bg-white border rounded-lg shadow-sm">
<p class="text-sm text-gray-500">Total Mahasiswa</p>
<p class="text-2xl font-bold text-green-600">
{{ $totalMahasiswa }}
</p>
</div>

<div class="p-4 bg-white border rounded-lg shadow-sm">
<p class="text-sm text-gray-500">Total Dosen</p>
<p class="text-2xl font-bold text-blue-600">
{{ $totalDosen }}
</p>
</div>

<div class="p-4 bg-white border rounded-lg shadow-sm">
<p class="text-sm text-gray-500">Total Prodi</p>
<p class="text-2xl font-bold text-purple-600">
{{ $totalProdi }}
</p>
</div>

<div class="p-4 bg-white border rounded-lg shadow-sm">
<p class="text-sm text-gray-500">Total Fakultas</p>
<p class="text-2xl font-bold text-orange-600">
{{ $totalFakultas }}
</p>
</div>

</div>


{{-- INFORMASI SISTEM --}}
<div class="p-5 bg-white border rounded-lg shadow-sm">

<h3 class="mb-3 text-lg font-semibold">
Informasi Sistem
</h3>

<ul class="space-y-2 text-sm text-gray-600">

<li>
Sistem ini digunakan untuk mengelola proses
<strong>PKL / Magang Mahasiswa</strong>.
</li>

<li>
Admin dapat mengelola data:
<ul class="pl-5 mt-1 list-disc">
<li>Fakultas</li>
<li>Program Studi</li>
<li>Dosen</li>
<li>Mahasiswa</li>
</ul>
</li>

<li>
Pastikan data master telah lengkap sebelum mahasiswa melakukan pengajuan PKL.
</li>

</ul>

</div>

</div>

</x-app-layout>