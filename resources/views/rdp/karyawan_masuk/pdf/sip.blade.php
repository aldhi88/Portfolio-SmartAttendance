<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SIP</title>
</head>
<body>
    <h1>SIP</h1>

    <div style="margin-top: 40px; width: 260px; text-align: center;">
        <div>{{ \App\Repositories\RdpManagerAccountRepo::getPrintRoleName($managerHcRegion) ?? 'Manager HC Region' }}</div>
        @php
            $ttdPath = $managerHcRegion?->ttd
                ? storage_path('app/public/' . \App\Repositories\RdpManagerAccountRepo::FILE_DIR_TTD . '/' . $managerHcRegion->ttd)
                : null;
        @endphp
        <div style="height: 80px; margin: 12px 0;">
            @if ($ttdPath && file_exists($ttdPath))
                <img src="{{ $ttdPath }}" alt="Tanda tangan" style="max-height: 80px; max-width: 180px;">
            @endif
        </div>
        <strong>{{ $managerHcRegion?->nickname ?? '-' }}</strong>
    </div>
</body>
</html>
