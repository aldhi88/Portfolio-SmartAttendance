<!doctype html>
<html lang="id">
<head>
    <title>Laporan Bulanan Lembur</title>
    <meta charset="utf-8">
    @include('lembur.pdf.style')
    <style>
        body{
            margin: 2cm 2cm 2cm 2cm;
            font-size: 12px !important;
        }
    </style>
</head>
<body>

<span>
    @foreach ($dt['emp'] as $item)
        <div style="
            text-decoration: underline;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            text-align: center
            ">DAFTAR KERJA LEMBUR BULAN {{$dt['attr']['monthLabel']}} {{$dt['attr']['year']}}</div>

        <div style="height: 10px"></div>
        <table style="font-weight: bold">
            <tr>
                <td style="width: 100px">Nama</td>
                <td style="width: 50px">:</td>
                <td>{{ $item['name'] }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $item['master_positions']['name'] }}</td>
            </tr>
        </table>
        <div style="height: 10px"></div>

        <table class="table-border table-padding-sm">
            <tr>
                <td class="vm hc" style="width: 110px">Tanggal</td>
                <td class="vm hc" style="width: 80px">Kerja Lembur <br> Dari - Sampai</td>
                <td class="vm hc" style="width: 50px">Jumlah</td>
                <td class="vm hc">Uraian Pekerjaan</td>
            </tr>
            @php
                $grand_total_jam = 0;
            @endphp
            @foreach ($item['data_lemburs'] as $data)
                <tr>
                    <td class="hc">
                        {{ \Carbon\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('d F Y') }}
                    </td>
                    <td class="hc">
                        @php
                            if ($data['start_carbon']->isSameDay($data['end_carbon'])) {
                                $hasil = sprintf(
                                    '%s - %s',
                                    $data['start_carbon']->format('H:i'),
                                    $data['end_carbon']->format('H:i')
                                );
                            } else {
                                $hasil = sprintf(
                                    '%s - %s %s WIB',
                                    $data['start_carbon']->format('H:i'),
                                    $data['end_carbon']->translatedFormat('d/m/Y'),
                                    $data['end_carbon']->format('H:i')
                                );
                            }
                            echo $hasil;
                        @endphp
                    </td>
                    <td class="hc">
                        {{ $data['total_jam'] }}
                        @php
                            $grand_total_jam+=$data['total_jam'];
                        @endphp
                    </td>
                    <td>{{$data['pekerjaan']}}</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="2" class="hc">Total Jam Lembur</td>
                    <td class="hc">{{$grand_total_jam}}</td>
                    <td></td>
                </tr>
        </table>
        <div style="height: 30px"></div>
    @endforeach
</span>

<div id="ttd"
    style="
        page-break-inside: avoid;
        break-inside: avoid;
        display: block;
     ">

    <table>
        <tr>
            <td>
                Dumai, {{ \Carbon\Carbon::parse(date('d M Y'))->locale('id')->translatedFormat('d F Y') }} <br>
                {{ $dt['manager']['master_positions']['name'] }} <br>
                Pjs.
            </td>
        </tr>
        <tr style="font-weight: bold; text-decoration: underline">
            <td>
                @if (is_null($dt['manager']['ttd']))
                    <div style="height: 100px"></div>
                @else
                    <img src="{{ $dt['manager']['path_ttd'] }}" alt="" class="img-fluid" height="100"><br>
                @endif

                {{ $dt['manager']['name'] }}
            </td>
        </tr>

    </table>
</div>

</body>
</html>
