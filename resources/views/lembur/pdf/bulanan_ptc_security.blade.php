<!doctype html>
<html lang="id">
<head>
    <title>{{ uniqId().'.pdf' }}</title>
    <meta charset="utf-8">
    @include('lembur.pdf.style_landscape')
    <style>
        body{
            margin: 1cm 1cm 1cm 1cm;
            font-size: 10px !important;
        }
    </style>
</head>
<body>

<div class="hc">
    <label style="font-size: 16px">
        <strong>MAPPING LEMBUR BULAN {{ strtoupper($dt['attr']['monthLabel']) }} {{$dt['attr']['year']}} IT DUMAI</strong>
    </label>
</div>
<div style="height: 10px"></div>
<div>
    <table class="table-border table-padding-sm" style="font-size: 9px">
        <tr>
            <td class="hc vm" style="font-weight: bold;line-height: 12px">NOPEK</td>
            <td class="hc vm" style="font-weight: bold;line-height: 12px">NAMA</td>
            <td class="hc vm" style="font-weight: bold;line-height: 12px">WILAYAH</td>
            <td class="hc vm" style="font-weight: bold;line-height: 12px">LOKASI</td>
            <td class="hc vm" style="font-weight: bold;line-height: 12px">JENIS <br> PEKERJAAN</td>
            <td class="hc vm" style="font-weight: bold;line-height: 12px">TANGGAL</td>
            <td class="hc vm" style="font-weight: bold;line-height: 12px">JAM <br> MULAI</td>
            <td class="hc vm" style="font-weight: bold;line-height: 12px">JAM <br> SELESAI</td>
            <td class="hc vm" style="font-weight: bold;line-height: 12px">JUMLAH</td>
            <td class="hc vm" style="font-weight: bold;line-height: 12px">AKTIVITAS</td>
        </tr>

        @foreach ($dt['emp'] as $item)
            @foreach ($item['data_lemburs'] as $lem)
                <tr>
                    <td class="hc">{{ $item['number'] }}</td>
                    <td class="hc">{{ $item['name'] }}</td>
                    <td class="hc">{{ "MOR I" }}</td>
                    <td class="hc">{{ $item['master_locations']['name'] }}</td>
                    <td class="hc">{{ $item['master_schedules'][0]['type'] }}</td>
                    <td class="hc">{{ \Carbon\Carbon::parse($lem['tanggal'])->format('d/m/Y') }}</td>
                    <td class="hc">{{ \Carbon\Carbon::parse($lem['laporan_lembur_checkin'])->format('H:i') }}</td>
                    <td class="hc">{{ \Carbon\Carbon::parse($lem['laporan_lembur_checkout'])->format('H:i') }}</td>
                    <td class="hc">{{ $lem['total_jam'] }}</td>
                    <td>{{ $lem['pekerjaan'] }}</td>
                </tr>
            @endforeach
            <tr style="background-color: rgb(244, 244, 244)">
                <td style="color: white;height: 5px" colspan="10"></td>
            </tr>
        @endforeach
    </table>
</div>


<div style="height: 30px"></div>

<div id="ttd"
    style="
        page-break-inside: avoid;
        break-inside: avoid;
        display: block;
     ">

    <table>
        <tr>
            <td style="width: 20%">
                Dumai, {{ \Carbon\Carbon::parse(date('d M Y'))->locale('id')->translatedFormat('d F Y') }} <br>
                Dibuat Oleh <br>
                PT. Pertamina Training & Consulting <br>
                Danru IT Dumai
            </td>
            <td style="width: 20%"></td>
            <td style="width: 20%">
                <span style="color: white">-</span> <br>
                Mengetahui <br>
                PT. Pertamina Training & Consulting <br>
                Koordinator Lapangan MOR I
            </td>
            <td style="width: 20%"></td>
            <td style="width: 20%">
                <span style="color: white">-</span> <br>
                Menyetujui, <br>
                PT. Pertamina Patra Niaga <br>
                Integrated Terminal Manager Dumai
            </td>
        </tr>
        <tr style="font-weight: bold; text-decoration: underline">
            <td>
                <div style="height: 100px"></div>
                Indra Warman
            </td>
            <td></td>
            <td>
                <div style="height: 100px"></div>
                Efendi Sihombing
            </td>
            <td></td>
            <td>
                <div style="height: 100px"></div>
                {{ $dt['manager']['name'] }}
            </td>
        </tr>

    </table>

</div>

</body>
</html>
