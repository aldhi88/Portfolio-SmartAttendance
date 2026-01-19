<!doctype html>
<html lang="id">
<head>
    <title>Laporan Bulanan Lembur</title>
    <meta charset="utf-8">
    @include('lembur.pdf.style')
    <style>
        body{
            margin: 1cm 1cm 1cm 1cm;
            font-size: 12px !important;
        }
    </style>
</head>
<body>

@foreach ($dt['emp'] as $key=>$item)

<div
    style="
    border: solid 1px black;
    padding: 15px;
    ">
    <div class="hc" style=""><strong>FORM PENGAJUAN LEMBUR PENGEMUDI OPERASIONAL POOL</strong></div>
    <div style="height: 10px"></div>
    <table style="font-weight: bold; line-height: 16px">
        <tr>
            <td style="width: 70px">NAMA</td>
            <td style="width: 10px">:</td>
            <td>{{ $item['name'] }}</td>
            <td style="width: 300px"></td>
            <td style="width: 110px">PERIODE</td>
            <td style="width: 10px">:</td>
            <td>{{ $dt['attr']['monthLabel'] }} {{ $dt['attr']['year'] }}</td>
        </tr>
        <tr>
            <td>JABATAN</td>
            <td>:</td>
            <td>{{ $item['master_positions']['name'] }}</td>
            <td style="width: 150px"></td>
            <td>LOKASI KERJA</td>
            <td>:</td>
            <td>IT Dumai</td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>:</td>
            <td>{{ $item['number'] }}</td>
            <td style="width: 150px"></td>
            <td>PROJECT</td>
            <td>:</td>
            <td></td>
        </tr>
    </table>
    <div style="height: 10px"></div>
    <table class="table-border table-padding-sm hc vm" style="font-size: 10px; line-height: 14px">
        <tr class="hc vm">
            <td class="hc vm" rowspan="2">NO</td>
            <td class="hc vm" rowspan="2">HARI</td>
            <td class="hc vm" rowspan="2">TANGGAL</td>
            <td class="hc vm" colspan="2">WAKTU KERJA</td>
            <td class="hc vm" colspan="3">TOTAL JAM</td>
            <td class="hc vm" rowspan="2">KETERANGAN</td>
        </tr>
        <tr>
            <td class="hc vm">MULAI</td>
            <td class="hc vm">SELESAI</td>
            <td class="hc vm">KERJA</td>
            <td class="hc vm">LEMBUR <br> NYATA</td>
            <td class="hc vm">LEMBUR <br> KONVERSI</td>
        </tr>

        @foreach ($dt['listHari'] as $i=>$day)
            <tr>
                <td class="hc">{{ $i+1 }}</td>
                <td class="hc" style="color: {{ $day=='Sabtu'||$day=='Minggu'?'red':null }}">{{ $day }}</td>
                <td class="hc" style="color: {{ $day=='Sabtu'||$day=='Minggu'?'red':null }}">{{ $dt['listTanggal'][$i] }}</td>
                <td class="hc" style="color: {{ $day=='Sabtu'||$day=='Minggu'?'red':null }}">{{ $item['absensi'][$i]['time_in'] }}</td>
                <td class="hc" style="color: {{ $day=='Sabtu'||$day=='Minggu'?'red':null }}">{{ $item['absensi'][$i]['time_out'] }}</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
            </tr>
        @endforeach

    </table>
</div>

@if ($key+1 < count($dt['emp']))
<div style="page-break-before: always;"></div>
@endif
@endforeach


</body>
</html>
