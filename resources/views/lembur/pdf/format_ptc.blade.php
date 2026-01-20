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
                <span><strong>No.{{ $data['nomor'] }}</strong></span>
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
        <tr>
            <td>Pekerjaan Dilemburkan</td>
            <td>:</td>
            <td>{{ $data['pekerjaan'] }}</td>
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
                    if ($data['data_lembur']['start_carbon']->isSameDay($data['data_lembur']['end_carbon'])) {
                        $hasil = sprintf(
                            '%s WIB s/d %s WIB',
                            $data['data_lembur']['start_carbon']->format('H:i:s'),
                            $data['data_lembur']['end_carbon']->format('H:i:s')
                        );
                    } else {
                        $hasil = sprintf(
                            '%s WIB s/d %s %s WIB',
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
            <td></td>
            <td></td>
            <td>
                <strong>
                    Total :
                    {{ $data['data_lembur']['hours'] }} Jam
                    {{ ($data['data_lembur']['minutes']>0)?$data['data_lembur']['minutes'].' Menit':null }}
                </strong>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                <strong>
                    Total Jam Lembur Bulan Berjalan :
                    {{ $data['data_lembur']['monthly_hours'] }} Jam
                    {{ ($data['data_lembur']['monthly_minutes']>0)?$data['data_lembur']['monthly_minutes'].' Menit':null }}
                </strong>

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
            <td colspan="3" style="height: 0px"></td>
        </tr>
        <tr style="font-weight: bold; text-decoration: underline">
            <td>
                @if (is_null($data['pengawas2']['ttd']))
                    <div style="height: 100px"></div>
                @else
                    <img src="{{ $data['pengawas2']['path_ttd'] }}" alt="" class="img-fluid" height="100"><br>
                @endif
                {{ $data['pengawas2']['name'] }}
            </td>
            <td></td>
            <td>
                @if (is_null($data['pengawas1']['ttd']))
                    <div style="height: 100px"></div>
                @else
                    <img src="{{ $data['pengawas1']['path_ttd'] }}" alt="" class="img-fluid" height="100"><br>
                @endif
                {{ $data['pengawas1']['name'] }}
            </td>
        </tr>
    </table>

    <div style="page-break-before: always;"></div>

    <table>
        <tr>
            <td style="text-align: right">
                <div>
                    <img src="{{ public_path('assets/images/logo-ptc.png') }}" width="150" alt=""
                    style="filter: grayscale(100%);">
                </div>
            </td>
        </tr>
    </table>

    <div>Dumai, {{ \Carbon\Carbon::parse(date('d M Y'))->locale('id')->translatedFormat('d F Y') }}</div>
    <div style="height: 30px"></div>
    <div>Dengan ini mengijinkan/menugaskan :</div>
    <table>
        <tr>
            <td style="width: 170px">Nama</td>
            <td style="width: 20px">:</td>
            <td style="font-weight: bold;border-bottom: 1px dotted #333;">{{ $data['data_employees']['name'] }}</td>
        </tr>
        <tr>
            <td>No. Pekerja</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;">{{ $data['data_employees']['number'] }}</td>
        </tr>
        <tr>
            <td>Pangkat/Golongan</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;">Biasa</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;">{{ $data['data_employees']['master_positions']['name'] }}</td>
        </tr>
        <tr>
            <td colspan="3" style="height: 20px"></td>
        </tr>
        <tr>
            <td>Untuk Melaksanakan</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;">{{ $data['pekerjaan'] }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td style="border-bottom: 1px dotted #333;"></td>
        </tr>
        <tr>
            <td>Proyek/Kegiatan</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;">-</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;">{{ \Carbon\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Terhitung mulai jam</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;">
                <strong>{{ $data['data_lembur']['start_carbon']->format('H:i:s') }} WIB</strong>
            </td>
        </tr>
        <tr>
            <td>Sampai dengan jam</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;">
                @php
                    if ($data['data_lembur']['start_carbon']->isSameDay($data['data_lembur']['end_carbon'])) {
                        $hasil = sprintf(
                            '%s WIB',
                            $data['data_lembur']['end_carbon']->format('H:i:s')
                        );
                    } else {
                        $hasil = sprintf(
                            '%s %s WIB',
                            $data['data_lembur']['end_carbon']->translatedFormat('d F Y'),
                            $data['data_lembur']['end_carbon']->format('H:i:s')
                        );
                    }
                @endphp
                <strong>{{ $hasil }}</strong>
            </td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;">Dumai</td>
        </tr>
        <tr>
            <td>Biaya ditanggung oleh</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;">Perusahaan</td>
        </tr>
        <tr>
            <td>Cost Center</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #333;"><strong>2222I51214</strong></td>
        </tr>
    </table>

    <div style="height: 10px"></div>
    <table class="table-border table-padding-sm">
        <tr>
            <td colspan="4" style="height: 60px"><span style="text-decoration: underline">Keterangan lain :</span></td>
        </tr>
        <tr>
            <td style="width: 25%">
                Ybs <br>
                @if (is_null($data['data_employees']['ttd']))
                    <div style="height: 100px"></div>
                @else
                    <img src="{{ public_path('storage/employees/ttd/' . $data['data_employees']['ttd']) }}" alt="" class="img-fluid" height="100"><br>
                @endif
                <div style="text-align: left"><strong>{{ $data['data_employees']['name'] }}</strong></div>
            </td>
            <td style="width: 25%">
                Diketahui, <br>
                Security IT Dumai
                @if (is_null($data['security']['ttd']))
                    <div style="height: 100px"></div>
                @else
                    <img src="{{ public_path('storage/employees/ttd/' . $data['security']['ttd']) }}" alt="" class="img-fluid" height="100"><br>
                @endif
                <div style="text-align: left"><strong>{{ $data['security']['name'] }}</strong></div>
            </td>
            <td style="width: 25%">
                Mengetahui, <br>
                Adm. Region Manager I
            </td>
            <td style="width: 25%">
                Mengetahui, <br>
                Manager MPS
            </td>
        </tr>
    </table>
    <div>Note: Wajib melapor hasil lembur keatasan yang bersangkutan</div>
    <div style="height: 30px"></div>
    <div>Ketentuan : </div>
    <table>
        @php
            $desc = [
                'Setiap pekerja lembur wajib mengisi SPKL',
                'Pekerja lembur atas perintah atasan ybs',
                'Membuat rekapan setiap bulan atas lembur masing - masing pekerja',
                'Lembur di hitung mulai jam 16:00 menit',
                'Apabila pekerja lembur kurang dari 30 menit dianggap tidak melaksanakan lembur',
                'Apabila pekerja lembur lebih dari 30 menit, maka dihitung lembur mulai 16:00 menit'
            ];
        @endphp
        @for ($i=0;$i<6;$i++)
            <tr>
                <td style="width: 70px"></td>
                <td>0{{$i+1}}</td>
                <td style="width: 10px">:</td>
                <td>{{ $desc[$i] }}</td>
            </tr>
        @endfor
    </table>



</body>
</html>
