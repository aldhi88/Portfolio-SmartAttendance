<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Aset Standar RDP</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        h1 { font-size: 16px; margin: 0 0 4px; }
        .meta { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px; vertical-align: top; }
        th { background: #eee; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Aset Standar RDP</h1>
    <div class="meta">
        <div>Tanggal Cetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
        <div>Cluster: {{ $cluster?->nama_cluster ?: 'Semua Cluster' }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="35">No</th>
                <th>Cluster</th>
                <th>Aset</th>
                <th>Jenis</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->rdp_master_clusters?->nama_cluster ?: '-' }}</td>
                    <td>{{ $item->rdp_master_asets?->perlengkapan ?: '-' }}</td>
                    <td>{{ $item->jenis ?: '-' }}</td>
                    <td class="text-center">{{ $item->jumlah ?: '-' }}</td>
                    <td class="text-center">{{ $item->satuan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Data aset standar tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
