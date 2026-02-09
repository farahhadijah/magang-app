@props(['timeline'])

@php
    $steps = [
        'pengajuan'  => 'Pengajuan PKL',
        'verifikasi' => 'Verifikasi',
        'berjalan'   => 'PKL Berjalan',
        'selesai'    => 'Selesai',
    ];
@endphp

<div class="flex items-center justify-between mt-4">
    @foreach ($steps as $key => $label)
        <div class="flex-1 text-center">
            <div
                class="mx-auto w-8 h-8 rounded-full flex items-center justify-center font-semibold
                {{ $timeline[$key] ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                {{ $loop->iteration }}
            </div>

            <p class="mt-2 text-sm
                {{ $timeline[$key] ? 'text-green-600 font-semibold' : 'text-gray-500' }}">
                {{ $label }}
            </p>
        </div>

        @if (!$loop->last)
            <div class="flex-1 h-1 mx-1
                {{ $timeline[$key] ? 'bg-green-600' : 'bg-gray-300' }}">
            </div>
        @endif
    @endforeach
</div>
