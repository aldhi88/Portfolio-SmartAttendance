<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report['title'] }}</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; }
        h1 { font-size: 16px; margin: 0 0 4px; }
        h2 { font-size: 12px; margin: 0 0 8px; }
        .meta { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px; vertical-align: top; }
        th { background: #eee; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h1>{{ $report['title'] }}</h1>
    <h2>{{ $report['variant_label'] }}</h2>
    <div class="meta">
        <div>Tanggal Cetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
        <div>Periode: {{ $filters['date_from'] ?: 'Awal' }} s/d {{ $filters['date_to'] ?: 'Akhir' }}</div>
        <div>Status: {{ $filters['status'] ?: 'Semua' }}</div>
        @if (!empty($filters['status_rumah']))
            <div>Status Rumah: {{ $filters['status_rumah'] }}</div>
        @endif
        @if (!empty($filters['rumah_id']))
            <div>Unit Rumah ID: {{ $filters['rumah_id'] }}</div>
        @endif
        @if (!empty($filters['status_aset']))
            <div>Status Aset: {{ $filters['status_aset'] }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="30">No</th>
                @foreach ($report['columns'] as $column)
                    <th class="{{ ($column['align'] ?? '') === 'center' ? 'text-center' : '' }}">{{ $column['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($report['rows'] as $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    @foreach ($report['columns'] as $column)
                        <td class="{{ ($column['align'] ?? '') === 'center' ? 'text-center' : '' }}">
                            {{ $row[$column['key']] ?? '-' }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($report['columns']) + 1 }}" class="text-center">Data laporan tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
