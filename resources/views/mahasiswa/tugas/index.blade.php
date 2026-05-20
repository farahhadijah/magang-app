<x-app-layout>
    <x-slot name="title">
        Tugas PKL - MagangApp
    </x-slot>

    <div class="py-6">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 border-b md:border-gray-200">
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

                    <!-- Tabel / Card Responsive -->
                    <div class="border border-gray-200 rounded-lg">

                        <!-- ===== DESKTOP (TABLE) ===== -->
                        <div class="hidden overflow-x-auto md:block">
                            <table class="min-w-full divide-y divide-green-200">
                                <thead class="bg-green-100">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Judul Tugas</th>
                                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase"> Dibuat </th>
                                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Deadline</th>
                                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($tugas as $t)
                                        @php
                                            $submit = $t->submit->where('id_pkl',$t->id_pkl)->first();
                                            $deadline = \Carbon\Carbon::parse($t->deadline);
                                            $now = \Carbon\Carbon::now();
                                            $isOverdue = $deadline->isPast() && !$submit;
                                        @endphp

                                        <tr class="{{ $isOverdue ? 'bg-red-50' : '' }}">
                                            <td class="px-6 py-4">
                                                <p class="text-sm font-medium">{{ $t->judul }}</p>
                                            </td>

                                            <td class="px-6 py-4">
                                                <span class="text-sm text-gray-700">
                                                    {{ $t->created_at->format('d M Y') }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4">
                                                <span class="{{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                                                    {{ $deadline->format('d M Y') }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4">
                                                {{-- STATUS TETAP --}}
                                                @if(!$submit)
                                                    <span class="px-3 py-1 text-xs bg-gray-200 rounded-full">Belum</span>
                                                @elseif($submit->revisi)
                                                    <span class="px-3 py-1 text-xs bg-red-200 rounded-full">Revisi</span>
                                                @elseif($submit->status == 'pending')
                                                    <span class="px-3 py-1 text-xs bg-yellow-200 rounded-full">Pending</span>
                                                @else
                                                    <span class="px-3 py-1 text-xs bg-green-200 rounded-full">Selesai</span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('mahasiswa.tugas.show',$t->id) }}"
                                                class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-10 text-center">Belum ada tugas</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- ===== MOBILE (CARD) ===== -->
                        <div class="space-y-4 md:hidden">
                            @forelse($tugas as $t)
                                @php
                                    $submit = $t->submit->where('id_pkl',$t->id_pkl)->first();
                                    $deadline = \Carbon\Carbon::parse($t->deadline);
                                    $now = \Carbon\Carbon::now();
                                    $isOverdue = $deadline->isPast() && !$submit;
                                @endphp

                                <div class="p-4 bg-white border rounded-lg shadow-sm {{ $isOverdue ? 'border-red-300 bg-red-50' : '' }}">
                                    
                                    <!-- Judul -->
                                    <h3 class="text-sm font-semibold text-gray-800">
                                        {{ $t->judul }}
                                    </h3>

                                    <div class="mt-2 text-xs">
                                        <span class="text-gray-500">Dibuat:</span><br>
                                        <span class="text-gray-800">
                                            {{ $t->created_at->format('d M Y') }}
                                        </span>
                                    </div>

                                    <!-- Deskripsi -->
                                    @if($t->deskripsi)
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ Str::limit($t->deskripsi, 80) }}
                                        </p>
                                    @endif

                                    <!-- Info -->
                                    <div class="flex flex-wrap items-center justify-between mt-3 text-xs">
                                        
                                        <!-- Deadline -->
                                        <div>
                                            <span class="text-gray-500">Deadline:</span><br>
                                            <span class="{{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-800' }}">
                                                {{ $deadline->format('d M Y') }}
                                            </span>
                                        </div>

                                        <!-- Status -->
                                        <div class="mt-2 sm:mt-0">
                                            @if(!$submit)
                                                <span class="px-3 py-1 bg-gray-200 rounded-full">Belum</span>
                                            @elseif($submit->revisi)
                                                <span class="px-3 py-1 bg-red-200 rounded-full">Revisi</span>
                                            @elseif($submit->status == 'pending')
                                                <span class="px-3 py-1 bg-yellow-200 rounded-full">Pending</span>
                                            @else
                                                <span class="px-3 py-1 bg-green-200 rounded-full">Selesai</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <div class="mt-4">
                                        <a href="{{ route('mahasiswa.tugas.show',$t->id) }}"
                                        class="block w-full px-4 py-2 text-sm text-center text-white bg-blue-600 rounded-lg">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>

                            @empty
                                <div class="py-10 text-center text-gray-500">
                                    Belum ada tugas
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>