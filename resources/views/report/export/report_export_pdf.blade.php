<style>
    body {
        font-family: sans-serif;
    }
    .page-break {
        page-break-after: always;
    }
    .section-title {
        text-align: center;
        margin-bottom: 8px;
        font-size: 12px;
    }
    .table-wrap {
        width: 100%;
        overflow: hidden;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 8px;
    }
    table, th, td {
        border: 1px solid #000;
    }
    th, td {
        padding: 2px;
        text-align: center;
        vertical-align: middle;
        word-wrap: break-word;
    }
    th[align="left"], td[align="left"] {
        text-align: left !important;
    }
</style>

@php
    use Carbon\Carbon;

    $chunks = array_chunk($tglCol, 10);
@endphp

@foreach ($chunks as $chunkIndex => $tglChunk)
    @php
        $firstDate = $tglChunk[0]['col_date'];
        $lastDate = $tglChunk[count($tglChunk) - 1]['col_date'];

        $monthName = Carbon::create($year, $month)->locale('id')->translatedFormat('F Y');
    @endphp

    <div class="section-title">
        <strong>LAPORAN ABSENSI</strong><br>
        <strong>{{ $firstDate ." - ". $lastDate }} {{ $monthName }}</strong>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama</th>
                    <th rowspan="2">Perusahaan</th>
                    <th rowspan="2">Jabatan</th>
                    @foreach ($tglChunk as $tgl)
                        <th colspan="2">{{ $tgl['col_day'] }} <br>{{ $tgl['col_date'] }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($tglChunk as $tgl)
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

                        @foreach ($tglChunk as $tgl)
                            @php
                                $key = str_pad($tgl['col_date'], 2, '0', STR_PAD_LEFT);
                                $absen = $item['absensi'][$key] ?? [];

                                $text_in = $absen['time_in'] ?? '-';
                                $label_in = $absen['label_in'] ?? '';
                                $shift = ($absen['type'] ?? '') === 'Rotasi' ? $absen['shift'] : null;

                                $text_out = $absen['time_out'] ?? '-';
                                $label_out = $absen['label_out'] ?? '';
                            @endphp

                            <td>
                                {{ $text_in }}
                                @if ($label_in)<br>{{ $label_in }}@endif
                                @if ($shift)<br>{{ $shift }}@endif
                            </td>
                            <td>
                                {{ $text_out }}
                                @if ($label_out)<br>{{ $label_out }}@endif
                                @if ($shift)<br>{{ $shift }}@endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
