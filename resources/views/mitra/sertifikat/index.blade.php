<x-app-layout>

    <x-slot name="title">
        Sertifikat - MagangApp
    </x-slot>

<div class="max-w-6xl px-3 py-6 mx-auto sm:px-4">

<h1 class="mb-6 text-xl font-bold text-slate-800 sm:text-2xl">
Pengajuan Sertifikat Mahasiswa
</h1>

@if(session('success'))
<div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-200 rounded-lg">
{{ session('success') }}
</div>
@endif

{{-- DESKTOP TABLE --}}
<div class="hidden overflow-x-auto bg-white border border-green-200 shadow-sm md:block rounded-xl">
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

            {{-- MODAL PREVIEW (Desktop) --}}
            @if($item->file_sertifikat)
            <div 
                id="modal-{{ $item->id }}" 
                class="fixed inset-0 z-[999999] items-center justify-center hidden bg-black bg-opacity-50">
                <div class="w-11/12 max-w-4xl p-4 bg-white rounded-lg shadow-lg">
                    <div class="flex items-center justify-between pb-2 mb-2 border-b">
                        <h2 class="text-lg font-semibold">Preview Sertifikat</h2>
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

{{-- MOBILE CARD - RESPONSIVE VERSION --}}
<div class="space-y-3 md:hidden">
    @foreach($pengajuan as $item)
        <div class="p-4 bg-white border border-gray-100 rounded-lg shadow">
            
            {{-- Header: Nama Mahasiswa --}}
            <div class="pb-2 mb-3 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">
                    {{ $item->pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
                </p>
            </div>

            {{-- Body: Status --}}
            <div class="mb-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Status</span>
                    @if($item->status == 'pending')
                        <span class="px-2 py-0.5 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                            Menunggu
                        </span>
                    @elseif($item->status == 'selesai')
                        <span class="px-2 py-0.5 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                            Selesai
                        </span>
                    @else
                        <span class="px-2 py-0.5 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">
                            {{ $item->status }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Action: Upload atau Preview --}}
            @if(!$item->file_sertifikat)
                <form action="{{ route('mitra.sertifikat.upload',$item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                    @csrf
                    <label class="block">
                        <span class="text-xs text-gray-500">Upload Sertifikat</span>
                        <input 
                            type="file" 
                            name="file_sertifikat"
                            class="w-full mt-1 text-sm border rounded focus:outline-none focus:ring focus:ring-green-200"
                            required
                        >
                    </label>
                    <button class="w-full py-2 text-sm font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700">
                        Upload Sertifikat
                    </button>
                </form>
            @else
                <button 
                    onclick="openModalMobile({{ $item->id }})"
                    class="w-full py-2 text-sm font-medium text-center text-white transition bg-green-600 rounded hover:bg-green-700">
                    Preview Sertifikat
                </button>
            @endif

        </div>

        {{-- MODAL PREVIEW (Mobile) --}}
        @if($item->file_sertifikat)
        <div 
            id="modal-mobile-{{ $item->id }}" 
            class="fixed inset-0 z-[999999] items-center justify-center hidden bg-black bg-opacity-50">
            <div class="w-11/12 max-w-4xl p-3 bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between pb-2 mb-2 border-b">
                    <h2 class="text-base font-semibold">Preview Sertifikat</h2>
                    <button 
                        onclick="closeModalMobile({{ $item->id }})"
                        class="text-xl text-gray-600 hover:text-red-600">
                        ✕
                    </button>
                </div>
                <div class="h-[60vh]">
                    <iframe
                        src="{{ asset('storage/'.$item->file_sertifikat) }}"
                        class="w-full h-full border rounded">
                    </iframe>
                </div>
            </div>
        </div>
        @endif

    @endforeach
</div>

</div>

<script>
    // Mobile Modal Functions
    function openModalMobile(id) {
        const modal = document.getElementById('modal-mobile-' + id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }
    
    function closeModalMobile(id) {
        const modal = document.getElementById('modal-mobile-' + id);
        if (modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    }
    
    // Desktop Modal Functions (if not exists already)
    if (typeof openModal === 'undefined') {
        function openModal(id) {
            const modal = document.getElementById('modal-' + id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
        
        function closeModal(id) {
            const modal = document.getElementById('modal-' + id);
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }
    }
</script>

</x-app-layout>