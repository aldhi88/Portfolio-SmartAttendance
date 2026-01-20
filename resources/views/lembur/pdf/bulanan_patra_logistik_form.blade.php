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
        @php
            $totaljam=0;
        @endphp
        @foreach ($dt['listHari'] as $i=>$day)
            <tr>
                <td class="hc">{{ $i+1 }}</td>
                <td class="hc" style="color: {{ $item['absensi'][$i]['label_in']=='off'?'red':null }}">{{ $day }}</td>
                <td class="hc" style="color: {{ $item['absensi'][$i]['label_in']=='off'?'red':null }}">{{ $dt['listTanggal'][$i] }}</td>
                <td class="hc" style="color: {{ $item['absensi'][$i]['label_in']=='off'?'red':null }}">{{ $item['absensi'][$i]['time_in'] }}</td>
                <td class="hc" style="color: {{ $item['absensi'][$i]['label_in']=='off'?'red':null }}">{{ $item['absensi'][$i]['time_out'] }}</td>
                <td></td>
                <td class="hc">
                    @php
                        $selisih = '-';
                        if(
                            $item['absensi'][$i]['label_in']=='lembur' &&
                            $item['absensi'][$i]['time_in']!='-' &&
                            $item['absensi'][$i]['time_out']!='-'
                        ){
                            $start = \Carbon\Carbon::createFromFormat('H:i:s', $item['absensi'][$i]['time_in']);
                            $end   = \Carbon\Carbon::createFromFormat('H:i:s', $item['absensi'][$i]['time_out']);
                            if ($end->lessThan($start)) {
                                $end->addDay();
                            }
                            $diffSeconds = $start->diffInSeconds($end);
                            $roundedSeconds = intdiv($diffSeconds, 1800) * 1800;
                            $selisih = $roundedSeconds / 3600;

                            $totaljam+=$selisih;
                        }

                        echo $selisih;
                    @endphp
                </td>
                <td></td>
                <td class="hc" style="color: {{ $item['absensi'][$i]['label_in']=='off'?'red':null }}">
                    @php
                        if(
                            $item['absensi'][$i]['label_in']=='lembur' &&
                            $item['absensi'][$i]['time_in']!='-' &&
                            $item['absensi'][$i]['time_out']!='-'
                        ){
                            $ket = $item['data_lemburs'][0]['pekerjaan'];
                        }elseif ($item['absensi'][$i]['label_in']=='off') {
                            $ket = 'OFF';
                        }else{
                            $ket = 'Operasional Kantor';
                        }

                        echo $ket;
                    @endphp
                </td>
            </tr>
        @endforeach

        <tr style="background-color: rgb(220, 220, 220)">
            <td colspan="6" class="hc">Total</td>
            <td class="hc">{{ $totaljam }}</td>
            <td></td>
            <td></td>
        </tr>

    </table>

    <table style="font-size: 7px">
        <tr>
            <td style="width: 40px">Catatan :</td>
            <td style="width: 5px">-</td>
            <td>Pengaturan jam kerja menyesuaikan ketetentuan Perusahaan</td>
        </tr>
        <tr>
            <td style="width: 40px"></td>
            <td style="width: 5px">-</td>
            <td>Standar jam kerja Perusahaan adalah 9 jam sudah termasuk istirahat 1 jam untuk 1 hari kerja</td>
        </tr>
        <tr>
            <td style="width: 40px"></td>
            <td style="width: 5px">-</td>
            <td>Batas maksimal jam lembur 72 jam konversi</td>
        </tr>
        <tr>
            <td style="width: 40px"></td>
            <td style="width: 5px">-</td>
            <td>Pengajuan lembur diverifikasi oleh Koordinator Lapangan Penyedia Jasa, User/Manager Fungsi User/Pejabat Pengguna dan Asset Management/Asset Operation Region</td>
        </tr>
        <tr>
            <td style="width: 40px"></td>
            <td style="width: 5px">-</td>
            <td>Dalam hal jam lembur konversi melebihi 72 jam, dapat diajukan maksimal s.d. 100 jam konversi dengan mengisi form pengajuan Rekapitulasi lembur yang ditandatangani oleh EGM/Level VP</td>
        </tr>
    </table>
</div>

@if ($key+1 < count($dt['emp']))
<div style="page-break-before: always;"></div>
@endif
@endforeach


</body>
</html>
