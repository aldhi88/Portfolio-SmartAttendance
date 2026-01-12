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

<div>
    <div style="text-align: right">
        <img src="{{ public_path('assets/images/logo-dark.png') }}" height="35px">
    </div>
    <label style="font-size: 16px">
        <strong>Justifikasi Kelebihan Jam Lembur Bulan {{$dt['attr']['monthLabel']}} {{$dt['attr']['year']}}</strong>
    </label>

    <table class="table-border table-padding-sm">
        <tr style="font-weight: bold">
            <td class="hc vm" style="width: 20px">No</td>
            <td class="hc vm" style="width: 190px">Nama Pekerja</td>
            <td class="hc vm" style="width: 120px">Nomor <br> Pekerja</td>
            <td class="hc vm" style="width: 190px">Jabatan/Fungsi</td>
            <td class="hc vm" colspan="2">Total Kelebihan <br> Jam Kerja</td>
            <td class="hc vm">Keterangan</td>
        </tr>

        @foreach ($dt['emp'] as $key=>$item)
            @php $grand_total_jam = 0; @endphp
            @foreach ($item['data_lemburs'] as $data)
                @php $grand_total_jam += $data['total_jam']; @endphp
            @endforeach
            @if ($grand_total_jam > 40)
                <tr>
                    <td class="hc">{{ $key+1 }}</td>
                    <td>{{$item['name']}}</td>
                    <td class="hc">{{$item['number']}}</td>
                    <td>{{ $item['master_positions']['name'] }} <br> Jr. Spv II Sales Service & General Affair</td>
                    <td class="hc" style="width: 30px">
                        @php
                            $grand_total_jam = 0;
                        @endphp
                        @foreach ($item['data_lemburs'] as $data)
                            @php
                                $grand_total_jam += $data['total_jam'];
                            @endphp
                        @endforeach
                        {{ $grand_total_jam }}
                    </td>
                    <td class="hc" style="width: 30px">Jam</td>
                    <td class="hc">-</td>
                </tr>
            @endif
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
