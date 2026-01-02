<!doctype html>
<html lang="id">
<head>
    <title>{{ uniqId().'.pdf' }}</title>
    <meta charset="utf-8">
    @include('lembur.pdf.style')
    <style>
        body{
            margin: 2cm 2cm 2cm 2cm;
            font-size: 14px !important;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td style="text-align: center">
                <div>
                    <img src="{{ public_path('assets/images/logo-dark.png') }}" width="150" alt=""
                    style="filter: grayscale(100%);">
                </div>
                <div style="height: 5px"></div>
                <span style="
                    text-decoration: underline;
                    font-weight: bold;
                    font-size: 18px
                    ">SURAT PERMINTAAN KERJA LEMBUR</span>
                <br>
                <span><strong>No.__________/PND448000/{{ date('Y') }}-S8</strong></span>
            </td>
        </tr>
    </table>

    <div style="height: 50px"></div>
    <table style="font-weight: bold">
        <tr>
            <td style="width: 170px">Kepada</td>
            <td style="width: 20px">:</td>
            <td>PT. PERTAMINA TRAINING & CONSULTING</td>
        </tr>

    </table>

    <div style="height: 20px"></div>
    <label>Mengharapkan bantuan Saudara untuk Menugaskan :</label>
    <table>
        <tr>
            <td style="width: 170px">Nama</td>
            <td style="width: 20px">:</td>
            <td style="font-weight: bold">{{ $data['data_employees']['name'] }}</td>
        </tr>
        <tr>
            <td>No. ID Card</td>
            <td>:</td>
            <td>{{ $data['data_employees']['number'] }}</td>
        </tr>
        <tr>
            <td>Bagian/Penempatan</td>
            <td>:</td>
            <td>{{ $data['data_employees']['master_positions']['name'] }}</td>
        </tr>
    </table>

    <div style="height: 10px"></div>
    <label>Untuk melaksanakan kerja lembur :</label>
    <table>
        <tr>
            <td style="width: 170px">Hari/Tanggal</td>
            <td style="width: 20px">:</td>
            <td>{{ \Carbon\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>
                @php
                    $start = \Carbon\Carbon::parse($data['work_time_lembur'])->locale('id');
                    $end   = \Carbon\Carbon::parse($data['checkout_time_lembur'])->locale('id');
                    if ($start->isSameDay($end)) {
                        $hasil = sprintf(
                            '%s WIB s/d %s WIB',
                            $start->format('H:i:s'),
                            $end->format('H:i:s')
                        );
                    } else {
                        $hasil = sprintf(
                            '%s WIB sampai %s %s WIB',
                            $start->format('H:i:s'),
                            $end->translatedFormat('d F Y'),
                            $end->format('H:i:s')
                        );
                    }

                    echo $hasil;
                @endphp
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                @php
                    $totalMinutes = $start->diffInMinutes($end);
                    $hours = intdiv($totalMinutes, 60);
                    $minutes = $totalMinutes % 60;

                    if ($minutes === 0) {
                        $durasi = $hours . ' Jam';
                    } else {
                        $durasi = $hours . ' Jam ' . $minutes . ' Menit';
                    }
                @endphp
                <strong>Total : {{ $durasi }}</strong>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                @php
                    $totalMinutes = $start->diffInMinutes($end);
                    $hours = intdiv($totalMinutes, 60);
                    $minutes = $totalMinutes % 60;

                    if ($minutes === 0) {
                        $durasi = $hours . ' Jam';
                    } else {
                        $durasi = $hours . ' Jam ' . $minutes . ' Menit';
                    }
                @endphp
                <strong>Total Jam Lembur Bulan Berjalan : {{ $durasi }}</strong>
            </td>
        </tr>
        <tr>
            <td>Lokasi</td>
            <td>:</td>
            <td>IT Dumai</td>
        </tr>
        <tr>
            <td>Biaya Ditanggung Oleh</td>
            <td>:</td>
            <td>PT. PERTAMINA TRAINING & CONSULTING</td>
        </tr>

        <tr>
            <td>Pekerjaan Dilemburkan</td>
            <td>:</td>
            <td>{{ $data['pekerjaan'] }}</td>
        </tr>
    </table>

    <div style="height: 130px"></div>
    <table>
        <tr>
            <td style="width: 40%">
                Mengetahui, <br>
                {{ $data['pengawas2']['master_positions']['name'] }}
            </td>
            <td style="width: 20%"></td>
            <td>
                Dumai, {{ \Carbon\Carbon::parse(date('d M Y'))->locale('id')->translatedFormat('d F Y') }}
                <br>
                {{ $data['pengawas1']['master_positions']['name'] }}
            </td>
        </tr>
        <tr>
            <td colspan="3" style="height: 80px"></td>
        </tr>
        <tr style="font-weight: bold; text-decoration: underline">
            <td>{{ $data['pengawas2']['name'] }}</td>
            <td></td>
            <td>{{ $data['pengawas1']['name'] }}</td>
        </tr>
    </table>



</body>
</html>
