<x-app-layout>
    <x-slot name="title">
        Pengajuan Sertifikat - MagangApp
    </x-slot>
    <div class="py-6">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-2xl font-semibold text-gray-800">
                        Pengajuan Sertifikat PKL
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Ajukan sertifikat untuk menyelesaikan kegiatan PKL Anda
                    </p>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 rounded-r-lg" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500 rounded-r-lg" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Mitra Alert -->
            @if(!$cekMitra['status'])
                <div class="p-6 mb-6 border-l-4 border-yellow-400 rounded-r-lg bg-yellow-50">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1 ml-3">
                            <p class="text-sm text-yellow-700">
                                {{ $cekMitra['message'] }}
                            </p>
                            @php
                                $wa = preg_replace('/[^0-9]/', '', $cekMitra['wa']);
                                if (substr($wa,0,1) == '0') {
                                    $wa = '62' . substr($wa,1);
                                }
                            @endphp
                            <div class="mt-4">
                                <a 
                                    href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Admin Prodi, saya ingin menanyakan pembuatan akun mitra untuk PKL.') }}"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.077 4.928C17.191 3.041 14.683 2 12.006 2 6.798 2 2.548 6.193 2.54 11.393c-.002 1.747.456 3.457 1.328 4.985L2.25 21.75l5.421-1.575c1.474.818 3.131 1.247 4.836 1.248h.004c5.19 0 9.452-4.194 9.46-9.396.004-2.51-.972-4.87-2.858-6.757zM12.012 20.04h-.003c-1.497 0-2.963-.404-4.222-1.163l-.303-.18-3.221.936.943-3.124-.187-.316c-.833-1.325-1.274-2.857-1.272-4.425.006-4.365 3.56-7.915 7.93-7.915 2.116 0 4.106.826 5.602 2.324 1.496 1.498 2.32 3.488 2.318 5.602-.007 4.366-3.562 7.916-7.925 7.916zm4.337-5.906c-.238-.12-1.41-.693-1.628-.772-.218-.08-.377-.12-.536.12-.158.238-.615.772-.754.93-.139.159-.278.179-.516.06-.953-.48-1.578-.856-2.216-1.465-.408-.39-.73-.85-.94-1.353-.099-.24.099-.372.222-.492.112-.108.247-.282.37-.422.124-.14.158-.24.238-.398.079-.159.04-.298-.02-.417-.06-.119-.537-1.292-.736-1.77-.194-.464-.39-.398-.536-.405-.139-.007-.298-.007-.456-.007-.159 0-.417.06-.635.298-.219.238-.834.813-.834 1.983 0 1.17.851 2.3.971 2.46.12.159 1.639 2.547 4.002 3.478.56.22.997.352 1.338.452.564.166 1.077.142 1.483.086.452-.062 1.41-.574 1.608-1.129.199-.555.199-1.03.14-1.13-.06-.099-.219-.159-.457-.278z"/>
                                    </svg>
                                    Hubungi Staff Prodi via WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content Card -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(!$pengajuan)
                        <!-- Form Pengajuan -->
                        <div class="text-center">
                            <div class="mb-6">
                                <div class="inline-flex items-center justify-center w-20 h-20 mb-4 bg-blue-100 rounded-full">
                                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="mb-2 text-lg font-medium text-gray-900">
                                    Belum Ada Pengajuan
                                </h3>
                                <p class="mb-6 text-sm text-gray-500">
                                    Anda belum mengajukan sertifikat PKL. Klik tombol di bawah untuk mengajukan.
                                </p>
                            </div>
                            
                            <form method="POST" action="{{ route('mahasiswa.sertifikat.store') }}" class="inline-block">
                                @csrf
                                <button 
                                    type="submit"
                                    class="inline-flex items-center px-6 py-3 text-sm font-medium text-white transition-colors duration-200 bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Ajukan Sertifikat Sekarang
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Tabel Status Pengajuan -->
                        <!-- Status Pengajuan -->
                        <div>

                        <h2 class="mb-4 text-lg font-semibold text-gray-800">
                            Status Pengajuan Sertifikat
                        </h2>

                        {{-- ================= DESKTOP TABLE ================= --}}
                        <div class="hidden overflow-x-auto border border-green-200 rounded-lg md:block">

                        <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-green-100">
                        <tr>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                        Tanggal Pengajuan
                        </th>

                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                        Status
                        </th>

                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                        Sertifikat
                        </th>
                        </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                        <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 whitespace-nowrap">

                        <div class="flex items-center">

                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>

                        <span class="text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d F Y H:i') }}
                        </span>

                        </div>

                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">

                        @php
                        $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'diproses' => 'bg-blue-100 text-blue-800',
                        'selesai' => 'bg-green-100 text-green-800',
                        'ditolak' => 'bg-red-100 text-red-800',
                        ];
                        $color = $statusColors[$pengajuan->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp

                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $color }}">
                        {{ ucfirst($pengajuan->status) }}
                        </span>

                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">

                        @if($pengajuan->file_sertifikat)

                        <button
                        onclick="openSertifikatModal()"
                        class="text-sm font-medium text-blue-600 hover:text-blue-800"
                        >
                        Lihat Sertifikat
                        </button>

                        @else

                        <span class="text-gray-500">
                        Belum tersedia
                        </span>

                        @endif

                        </td>

                        </tr>

                        </tbody>

                        </table>

                        </div>



                        {{-- ================= MOBILE CARD ================= --}}
                        <div class="space-y-4 md:hidden">

                        <div class="p-4 bg-white border border-green-200 rounded-lg shadow-sm">

                        <div class="mb-2 text-sm text-gray-500">
                        Tanggal Pengajuan
                        </div>

                        <div class="mb-3 text-sm font-medium text-gray-800">
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d F Y H:i') }}
                        </div>


                        <div class="mb-2 text-sm text-gray-500">
                        Status
                        </div>

                        <div class="mb-3">

                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $color }}">
                        {{ ucfirst($pengajuan->status) }}
                        </span>

                        </div>


                        <div class="mb-2 text-sm text-gray-500">
                        Sertifikat
                        </div>

                        @if($pengajuan->file_sertifikat)

                        <button
                        onclick="openSertifikatModal()"
                        class="w-full px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                        >
                        Lihat Sertifikat
                        </button>

                        @else

                        <span class="text-sm text-gray-500">
                        Belum tersedia
                        </span>

                        @endif

                        </div>

                        </div>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sertifikat -->
    @if($pengajuan && $pengajuan->file_sertifikat)
        <div id="modalSertifikat" 
            class="fixed inset-0 z-[99999] hidden overflow-y-auto bg-black bg-opacity-60 backdrop-blur-sm"
            onclick="if(event.target === this) closeSertifikatModal()">
            
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative w-full max-w-4xl bg-white shadow-2xl rounded-xl">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900">
                                Sertifikat PKL
                            </h3>
                        </div>
                        <button 
                            onclick="closeSertifikatModal()"
                            class="text-gray-400 transition-colors duration-200 hover:text-gray-600 focus:outline-none"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="p-6">
                        <iframe
                            src="{{ asset('storage/'.$pengajuan->file_sertifikat) }}"
                            class="w-full h-[600px] rounded-lg border border-gray-200"
                            frameborder="0"
                        >
                        </iframe>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end p-6 border-t bg-gray-50 rounded-b-xl">
                        <a 
                            href="{{ asset('storage/'.$pengajuan->file_sertifikat) }}" 
                            download
                            class="px-4 py-2 mr-2 text-sm font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                        <button 
                            onclick="closeSertifikatModal()"
                            class="px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@push('scripts')
<script>

function openSertifikatModal()
{
    const modal = document.getElementById('modalSertifikat');
    if(!modal) return;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeSertifikatModal()
{
    const modal = document.getElementById('modalSertifikat');
    if(!modal) return;

    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        closeSertifikatModal();
    }
});

</script>
@endpush
</x-app-layout>