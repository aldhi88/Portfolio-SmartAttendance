<table>
    <thead>
        <tr>
            <td colspan="{{ 4 + count($tglCol) * 2 }}" align="center">
                <strong>
                    LAPORAN ABSENSI <br>
                    {{ \Carbon\Carbon::create($year, $month)->locale('id')->translatedFormat('F Y') }}
                </strong>
            </td>
        </tr>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama</th>
            <th rowspan="2">Perusahaan</th>
            <th rowspan="2">Jabatan</th>
            @foreach ($tglCol as $tgl)
                <th colspan="2">{{ $tgl['col_day'] }} <br>{{ $tgl['col_date'] }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach ($tglCol as $tgl)
                <th>IN</th>
                <th>OUT</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['master_organizations']['name'] ?? '' }}</td>
                <td>{{ $item['master_positions']['name'] ?? '' }}</td>

                @foreach ($tglCol as $tgl)
                    @php
                        $key = str_pad($tgl['col_date'], 2, '0', STR_PAD_LEFT);
                        $absen = $item['absensi'][$key] ?? [];

                        $text_in = $absen['time_in'] ?? '-';
                        $label_in = $absen['label_in'] ?? '';
                        $shift = ($absen['type'] ?? '') === 'Rotasi' ? $absen['shift'] : null;

                        $text_out = $absen['time_out'] ?? '-';
                        $label_out = $absen['label_out']?? '';
                        $shift = ($absen['type'] ?? '') === 'Rotasi' ? $absen['shift'] : null;
                    @endphp

                    <td>
                        {{ $text_in }}
                        <br>
                        @if ($label_in)
                            {{ $label_in }}
                        @endif
                        @if ($shift)
                            <br>
                            {{ $shift }}
                        @endif
                    </td>
                    <td>
                        {{ $text_out }}
                        <br>
                        @if ($label_out)
                            {{ $label_out }}
                        @endif
                        @if ($shift)
                            <br>
                            {{ $shift }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
