<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Bulk Surat PKL</title>
</head>
<body>

@foreach($data as $item)
    @php
        $noSurat = '0'.$item->id.'/UNISLA/PKL/'.date('Y');
    @endphp

    @include('surat.pengantar', [
        'pengajuan' => $item,
        'noSurat' => $noSurat
    ])

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach

</body>
</html>