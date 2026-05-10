<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penempatan RDP</title>
    <style>
        body { font-family: sans-serif; font-size: 8px; }
        h1 { font-size: 16px; margin: 0 0 4px; }
        .meta { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 3px; vertical-align: top; }
        th { background: #eee; }
        .text-center { text-align: center; }
    </style>
</head>
@php
    $formatDate = fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '-';
@endphp
<body>
    <h1>Laporan Penempatan RDP</h1>
    <div class="meta">
        <div>Tanggal Cetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
        <div>Cluster: {{ $cluster?->nama_cluster ?: 'Semua Cluster' }}</div>
        <div>Status Rumah: {{ $filters['status_rumah'] ?? 'Semua' }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="25">No</th>
                <th>Nama Karyawan</th>
                <th>NOPek</th>
                <th>Organisasi</th>
                <th>Jabatan</th>
                <th>Lokasi</th>
                <th>Fungsi</th>
                <th>Cluster</th>
                <th>Blok</th>
                <th>Tipe</th>
                <th>Nomor</th>
                <th>Tanggal Mulai</th>
                <th>Nomor SK Mutasi</th>
                <th>Tanggal SK Mutasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                @php
                    $employee = $item->data_employees;
                    $rumah = $item->rdp_master_rumahs;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $employee?->name ?: '-' }}</td>
                    <td>{{ $employee?->number ?: '-' }}</td>
                    <td>{{ $employee?->master_organizations?->name ?: '-' }}</td>
                    <td>{{ $employee?->master_positions?->name ?: '-' }}</td>
                    <td>{{ $employee?->master_locations?->name ?: '-' }}</td>
                    <td>{{ $employee?->master_functions?->name ?: '-' }}</td>
                    <td>{{ $rumah?->rdp_master_clusters?->nama_cluster ?: '-' }}</td>
                    <td>{{ $rumah?->block ?: '-' }}</td>
                    <td>{{ $rumah?->tipe ?: '-' }}</td>
                    <td>{{ $rumah?->nomor ?: '-' }}</td>
                    <td>{{ $formatDate($item->tanggal_mulai) }}</td>
                    <td>{{ $item->nomor_sk_mutasi ?: '-' }}</td>
                    <td>{{ $formatDate($item->tanggal_sk_mutasi) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center">Data penempatan RDP tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
