<x-app-layout>
    <x-slot name="title">
        Tugas PKL - MagangApp
    </x-slot>

    <div class="py-6">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <h1 class="text-2xl font-semibold text-gray-800">
                                Tugas dari Mitra
                            </h1>
                            <p class="mt-1 text-sm text-gray-600">
                                Daftar tugas yang diberikan oleh mitra PKL Anda
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mitra Alert - Sama dengan halaman sebelumnya -->
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

                    <!-- Tabel Tugas -->
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-green-200">
                            <thead class="bg-green-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Judul Tugas
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Deadline
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Status
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($tugas as $t)
                                    @php
                                        $submit = $t->submit->where('id_pkl',$t->id_pkl)->first();
                                        
                                        // Cek deadline
                                        $deadline = \Carbon\Carbon::parse($t->deadline);
                                        $now = \Carbon\Carbon::now();
                                        $isOverdue = $deadline->isPast() && !$submit;
                                    @endphp

                                    <tr class="hover:bg-gray-50 transition-colors duration-200 {{ $isOverdue ? 'bg-red-50' : '' }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 bg-gray-200 rounded-full">
                                                    <span class="text-sm font-medium text-gray-600">
                                                        {{ $loop->iteration }}
                                                    </span>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $t->judul }}
                                                    </p>
                                                    @if($t->deskripsi)
                                                        <p class="max-w-xs text-xs text-gray-500 truncate">
                                                            {{ Str::limit($t->deskripsi, 50) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-sm text-gray-900 {{ $isOverdue ? 'font-semibold text-red-600' : '' }}">
                                                    {{ $deadline->format('d M Y') }}
                                                    @if($isOverdue)
                                                        <span class="ml-2 text-xs text-red-600">(Terlewat)</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(!$submit)
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 bg-gray-200 rounded-full">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Belum dikumpulkan
                                                </span>
                                            @elseif($submit->revisi)
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-800 bg-red-200 rounded-full">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                    Revisi
                                                </span>
                                            @elseif($submit->status == 'pending')
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-yellow-800 bg-yellow-200 rounded-full">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Pending
                                                </span>
                                            @elseif($submit->status == 'selesai')
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-200 rounded-full">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Selesai
                                                </span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <a href="{{ route('mahasiswa.tugas.show',$t->id) }}"
                                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Tugas</h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    Saat ini belum ada tugas yang diberikan oleh mitra.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>