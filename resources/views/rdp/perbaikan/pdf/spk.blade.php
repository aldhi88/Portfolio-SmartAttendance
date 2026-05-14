<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>SPK Perbaikan</title>
</head>
<body>
    <h1>SPK</h1>
    <div>No. {{ $item->nomor_spk_surat ?: '-' }}</div>
    <div>Tanggal: {{ $item->tanggal_spk_surat ?: '-' }}</div>

    <div style="margin-top: 40px; width: 260px; text-align: center;">
        <div>{{ \App\Repositories\RdpManagerAccountRepo::getPrintRoleName($managerAsetRegion) ?? 'Manager Aset Region' }}</div>
        @php
            $ttdPath = $managerAsetRegion?->ttd
                ? storage_path('app/public/' . \App\Repositories\RdpManagerAccountRepo::FILE_DIR_TTD . '/' . $managerAsetRegion->ttd)
                : null;
        @endphp
        <div style="height: 80px; margin: 12px 0;">
            @if ($ttdPath && file_exists($ttdPath))
                <img src="{{ $ttdPath }}" alt="Tanda tangan" style="max-height: 80px; max-width: 180px;">
            @endif
        </div>
        <strong>{{ $managerAsetRegion?->nickname ?? '-' }}</strong>
    </div>
</body>
</html>
