<x-app-layout>

    <x-slot name="title">
        Sertifikat - MagangApp
    </x-slot>
<div class="max-w-6xl p-6 mx-auto">

<h1 class="mb-6 text-2xl font-bold text-slate-800">
Pengajuan Sertifikat Mahasiswa
</h1>

@if(session('success'))
<div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-200 rounded-lg">
{{ session('success') }}
</div>
@endif


<div class="overflow-x-auto bg-white border border-green-200 shadow-sm rounded-xl">

<table class="min-w-full">

<thead class="text-sm text-gray-700 bg-green-100">
<tr>
<th class="p-3 text-left">Mahasiswa</th>
<th class="p-3 text-left">Tanggal</th>
<th class="p-3 text-left">Status</th>
<th class="p-3 text-left">Sertifikat</th>
</tr>
</thead>

<tbody class="text-sm text-gray-700">

@foreach($pengajuan as $item)

<tr class="border-t hover:bg-gray-50">

<td class="p-3 font-medium">
{{ $item->pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
</td>

<td class="p-3">
{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
</td>

<td class="p-3">

@if($item->status == 'pending')
<span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
Menunggu
</span>

@elseif($item->status == 'selesai')
<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
Selesai
</span>

@else
<span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">
{{ $item->status }}
</span>
@endif

</td>

<td class="p-3">

@if(!$item->file_sertifikat)

<form action="{{ route('mitra.sertifikat.upload',$item->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2 md:flex-row">
@csrf

<input 
type="file" 
name="file_sertifikat"
class="text-sm border rounded"
required
>

<button class="px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
Upload
</button>

</form>

@else

<button 
onclick="openModal({{ $item->id }})"
class="px-3 py-1 text-sm text-white bg-green-600 rounded hover:bg-green-700">
Preview Sertifikat
</button>

@endif

</td>

</tr>

{{-- MODAL PREVIEW --}}
@if($item->file_sertifikat)

<div 
id="modal-{{ $item->id }}" 
class="fixed inset-0 z-[999999] items-center justify-center hidden bg-black bg-opacity-50">

<div class="w-11/12 max-w-4xl p-4 bg-white rounded-lg shadow-lg">

<div class="flex items-center justify-between pb-2 mb-2 border-b">

<h2 class="text-lg font-semibold">
Preview Sertifikat
</h2>

<button 
onclick="closeModal({{ $item->id }})"
class="text-gray-600 hover:text-red-600">
✕
</button>

</div>

<div class="h-[70vh]">

<iframe
src="{{ asset('storage/'.$item->file_sertifikat) }}"
class="w-full h-full border rounded">
</iframe>

</div>

</div>

</div>

@endif

@endforeach

</tbody>

</table>

</div>

</div>

</x-app-layout>