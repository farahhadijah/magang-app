@php
    $map = [
        'pending'   => 'bg-yellow-100 text-yellow-800',
        'disetujui' => 'bg-green-100 text-green-800',
        'ditolak'   => 'bg-red-100 text-red-800',
    ];

    $label = [
        'pending'   => 'Menunggu',
        'disetujui' => 'Disetujui',
        'ditolak'   => 'Ditolak',
    ];
@endphp

<span class="px-3 py-1 text-sm font-semibold rounded {{ $map[$status] ?? $map['pending'] }}">
    {{ $label[$status] ?? 'Menunggu' }}
</span>
