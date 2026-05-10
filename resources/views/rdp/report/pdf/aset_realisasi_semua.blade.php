<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Aset RDP Realisasi Semua Unit</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; }
        h1 { font-size: 16px; margin: 0 0 4px; }
        h2 { font-size: 12px; margin: 14px 0 6px; }
        .meta { margin-bottom: 12px; }
        .unit-meta { margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; page-break-inside: avoid; }
        th, td { border: 1px solid #333; padding: 4px; vertical-align: top; }
        th { background: #eee; }
        .text-center { text-align: center; }
        .unit-block { page-break-inside: avoid; margin-bottom: 12px; }
    </style>
</head>
<body>
    <h1>Laporan Aset RDP Realisasi Semua Unit</h1>
    <div class="meta">
        <div>Tanggal Cetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
        <div>Total Unit Rumah: {{ $rumahs->count() }}</div>
    </div>

    @forelse ($rumahs as $rumah)
        <div class="unit-block">
            <h2>{{ \App\Repositories\RdpReportRepo::unitRumahLabel($rumah) }}</h2>
            <div class="unit-meta">
                Status Rumah: {{ $rumah->status ?: '-' }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th class="text-center" width="35">No</th>
                        <th>Aset</th>
                        <th>Jenis</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rumah->rdp_master_rumah_asets as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->rdp_master_asets?->perlengkapan ?: '-' }}</td>
                            <td>{{ $item->jenis ?: '-' }}</td>
                            <td class="text-center">{{ $item->jumlah ?: '-' }}</td>
                            <td class="text-center">{{ $item->satuan ?: '-' }}</td>
                            <td class="text-center">{{ $item->status ?: '-' }}</td>
                            <td>{{ $item->catatan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Data aset realisasi untuk unit rumah ini tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <table>
            <tbody>
                <tr>
                    <td class="text-center">Data unit rumah tidak ditemukan.</td>
                </tr>
            </tbody>
        </table>
    @endforelse
</body>
</html>
