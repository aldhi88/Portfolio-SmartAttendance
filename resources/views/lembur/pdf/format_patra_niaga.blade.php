<!doctype html>
<html lang="id">
<head>
    <title>{{ uniqId().'.pdf' }}</title>
    <meta charset="utf-8">
    @include('lembur.pdf.style')
    <style>
        body{
            margin: 2cm 1cm 1cm 1cm;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td>
                <span style="
                    text-decoration: underline;
                    font-weight: bold;
                    font-size: 18px
                    ">SURAT PERINTAH KERJA</span>
                <br>
                <span><strong>No. {{ $data['nomor'] }}</strong></span>
            </td>
            <td style="vertical-align: middle">
                <div style="text-align: right">
                    <img src="{{ public_path('assets/images/logo-grayscale.png') }}" width="150" alt=""
                    style="filter: grayscale(100%);">
                </div>
            </td>
        </tr>
    </table>

    <div style="height: 30px"></div>

    <label>Yang bertandatangan dibawah ini :</label>
    <div style="height: 10px"></div>
    <table style="font-weight: bold">
        <tr>
            <td style="width: 150px">Nama</td>
            <td style="width: 10px">:</td>
            <td>{{ $data['pengawas2']['name'] }}</td>
        </tr>
        <tr>
            <td>Nopek</td>
            <td>:</td>
            <td>{{ $data['pengawas2']['number'] }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $data['pengawas2']['master_positions']['name'] }}</td>
        </tr>
    </table>

    <div style="height: 10px"></div>
    <label>Memerintahkan untuk bekerja melebihi jam kerja normal kepada :</label>
    <div style="height: 10px"></div>
    <table style="font-weight: bold">
        <tr>
            <td style="width: 150px">Nama</td>
            <td style="width: 10px">:</td>
            <td>{{ $data['data_employees']['name'] }}</td>
        </tr>
        <tr>
            <td>Nopek</td>
            <td>:</td>
            <td>{{ $data['data_employees']['number'] }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $data['data_employees']['master_positions']['name'] }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>
                @php
                    if ($data['data_lembur']['start_carbon']->isSameDay($data['data_lembur']['end_carbon'])) {
                        $hasil = sprintf(
                            '%s WIB s/d %s WIB',
                            $data['data_lembur']['start_carbon']->format('H:i:s'),
                            $data['data_lembur']['end_carbon']->format('H:i:s')
                        );
                    } else {
                        $hasil = sprintf(
                            '%s WIB sampai %s %s WIB',
                            $data['data_lembur']['start_carbon']->format('H:i:s'),
                            $data['data_lembur']['end_carbon']->translatedFormat('d F Y'),
                            $data['data_lembur']['end_carbon']->format('H:i:s')
                        );
                    }
                    echo $hasil;
                @endphp
            </td>
        </tr>
        <tr>
            <td>Total Waktu</td>
            <td>:</td>
            <td>
                <strong>
                    {{ $data['data_lembur']['hours'] }} Jam
                    {{ ($data['data_lembur']['minutes']>0)?$data['data_lembur']['minutes'].' Menit':null }}
                </strong>
            </td>
        </tr>

        <tr>
            <td>Keterangan Pekerjaan</td>
            <td>:</td>
            <td>{{ $data['pekerjaan'] }}</td>
        </tr>
    </table>

    <div style="height: 30px"></div>

    <div>*) Klasifikasi Pekerjaan Mendesak (Lingkari syarat yang terpenuhi):</div>
    <ol style="padding-left: 18px;">
        <li>Bilamana terdapat pekerjaan-pekerjaan yang membahayakan kesehatan atau keselamatan masyarakat jika tidak segera dilaksanakan</li>
        <li>Menyelesaikan pekerjaan yang penting artinya bagi pembangunan negara sesuai dengan perintah dan petunjuk pemerintah</li>
        <li>Dalam menyelesaikan pekerjaan yang dapat mengakibatkan kerugian bagi perusahaan negara ataupun masyarakat jika tidak dikerjakan</li>
        <li>Pekerjaan Project seperti engineering procurement & construction, commisioning, Satgas BBM, Haji flight on call dan lain-lain</li>
    </ol>

    <div style="height: 30px"></div>
    <table style="font-weight: bold">
        <tr>
            <td style="width: 50%">Disetujui Oleh,</td>
            <td style="width: 20%"></td>
            <td>
                Dumai, {{ \Carbon\Carbon::parse(date('d M Y'))->locale('id')->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="3" style="height: 70px"></td>
        </tr>
        <tr>
            <td>{{ $data['data_employees']['name'] }}</td>
            <td></td>
            <td>{{ $data['pengawas2']['name'] }}</td>
        </tr>
    </table>

    <div style="height: 5"></div>
    <hr style="border: none; border-top: 1px dashed #000; width: 100%;">

    <div style="height: 5"></div>
    <label>Yang bertandatangan dibawah ini :</label>
    <div style="height: 10px"></div>
    <table style="font-weight: bold">
        <tr>
            <td style="width: 150px">Nama</td>
            <td style="width: 10px">:</td>
            <td>{{ $data['security']['name'] }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $data['security']['master_positions']['name'] }}</td>
        </tr>
    </table>

    <div style="height: 10"></div>
    <label>Menerangkan bahwa :</label>
    <div style="height: 5px"></div>
    <table style="font-weight: bold">
        <tr>
            <td style="width: 150px">Nama</td>
            <td style="width: 10px">:</td>
            <td>{{ $data['data_employees']['name'] }}</td>
        </tr>
        <tr>
            <td>Nopek</td>
            <td>:</td>
            <td>{{ $data['data_employees']['number'] }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $data['data_employees']['master_positions']['name'] }}</td>
        </tr>
    </table>

    <div style="height: 10"></div>
    <label>Telah bekerja melebihi jam kerja normal pada :</label>
    <div style="height: 5px"></div>
    <table style="font-weight: bold">
        <tr>
            <td style="width: 150px">Tanggal</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('d F Y') }}</td>
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
                            '%s WIB sampai %s WIB',
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
            <td>Total Jam Aktual</td>
            <td>:</td>
            <td>
                <strong>
                    {{ $data['data_lembur']['hours'] }} Jam
                    {{ ($data['data_lembur']['minutes']>0)?$data['data_lembur']['minutes'].' Menit':null }}
                </strong>
            </td>
        </tr>
    </table>

    <table style="font-weight: bold">
        <tr>
            <td style="width: 50%"></td>
            <td style="width: 20%"></td>
            <td>
                Dumai, {{ \Carbon\Carbon::parse(date('d M Y'))->locale('id')->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="3" style="height: 70px"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>{{ $data['security']['name'] }}</td>
        </tr>
    </table>


</body>
</html>
