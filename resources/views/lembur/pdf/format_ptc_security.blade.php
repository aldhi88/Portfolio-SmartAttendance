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

    <table>
        <tr>
            <td style="width: 47%">
                <table>
                    <tr>
                        <td style="width: 10%"></td>
                        <td style="text-align: center">
                            <div style="height: 5px"></div>
                            <span style="
                                font-weight: bold;
                                font-size: 12px;
                                line-height: 10px
                                ">SURAT PERMINTAAN KERJA LEMBUR</span>
                            <br>
                            <span><strong>No. {{ $data['nomor'] }}</strong></span>
                        </td>
                        <td style="width: 10%;text-align: right">
                            <img src="{{ public_path('assets/images/logo-dark.png') }}" height="35px">
                        </td>
                    </tr>
                </table>

                <div style="height: 30px"></div>
                <table>
                    <tr>
                        <td style="width: 50px">Kepada</td>
                        <td style="width: 20px">:</td>
                        <td>
                            Security Services Manager <br>PT. Pertamina Training & Consulting
                        </td>
                    </tr>

                </table>

                <div style="height: 20px"></div>
                <label>Mengharapkan bantuan Saudara untuk Menugaskan :</label>
                <div style="height: 10px"></div>
                <table>
                    <tr>
                        <td style="width: 170px">Nama</td>
                        <td style="width: 20px">:</td>
                        <td style="border-bottom: 1px dotted #333;">{{ $data['data_employees']['name'] }}</td>
                    </tr>
                    <tr>
                        <td>No. ID Card</td>
                        <td>:</td>
                        <td>{{ $data['data_employees']['number'] }}</td>
                    </tr>
                    <tr>
                        <td>Bagian/Penempatan</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;">{{ $data['data_employees']['master_positions']['name'] }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Pekerjaan</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;">-</td>
                    </tr>
                </table>

                <div style="height: 10px"></div>
                <label>Untuk melaksanakan kerja lembur :</label>
                <div style="height: 10px"></div>
                <table>
                    <tr>
                        <td style="width: 170px">Hari/Tanggal</td>
                        <td style="width: 20px">:</td>
                        <td style="border-bottom: 1px dotted #333;">{{ \Carbon\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Waktu Mulai/Selesai Pekerjaan</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;">
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
                        <td style="text-align: right">
                            <strong>
                                Jumlah :
                                {{ $data['data_lembur']['hours'] }} Jam
                                {{ ($data['data_lembur']['minutes']>0)?$data['data_lembur']['minutes'].' Menit':null }}
                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <td>Lokasi</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;">Integrated Terminal Dumai</td>
                        {{-- <td>{{ $data['data_employees']['master_locations']['name'] }}</td> --}}
                    </tr>
                    <tr>
                        <td>Biaya Ditanggung Oleh</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;">Perusahaan</td>
                    </tr>

                    <tr>
                        <td>Pekerjaan Dilemburkan</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;height: 50px">{{ $data['pekerjaan'] }}</td>
                    </tr>
                </table>

                <div style="height: 50px"></div>
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
                    <tr style="font-weight: bold">
                        <td>
                            @if (is_null($data['pengawas2']['ttd']))
                                <div style="height: 100px"></div>
                            @else
                                <img src="{{ $data['pengawas2']['path_ttd'] }}" alt="" class="img-fluid" height="100"><br>
                            @endif
                            <div style="border-bottom: 0.1px solid black;padding-bottom: 10px">
                                {{ $data['pengawas2']['name'] }}
                            </div>
                        </td>
                        <td></td>
                        <td>
                            @if (is_null($data['pengawas1']['ttd']))
                                <div style="height: 100px"></div>
                            @else
                                <img src="{{ $data['pengawas1']['path_ttd'] }}" alt="" class="img-fluid" height="100"><br>
                            @endif
                            <div style="border-bottom: 0.1px solid black;padding-bottom: 10px">
                                {{ $data['pengawas1']['name'] }}
                            </div>
                        </td>
                    </tr>
                </table>

            </td>
            <td style="width: 3%;border-right: 1px dashed #9a9a9a;"></td>
            <td style="padding-left: 20px;">
                <table>
                    <tr>
                        <td style="width: 10%"></td>
                        <td style="text-align: center">
                            <div style="height: 5px"></div>
                            <span style="
                                font-weight: bold;
                                font-size: 12px;
                                line-height: 10px
                                ">
                                PT. PERTAMINA TRAINING & CONSULTING <br>
                                SURAT PERINTAH KERJA LEMBUR
                            </span>
                        </td>
                        <td style="width: 10%;text-align: right">
                            <img src="{{ public_path('assets/images/logo-ptc.png') }}" height="35px">
                        </td>
                    </tr>
                </table>

                <div style="height: 30px"></div>
                <label>Dengan ini Menugaskan :</label>
                <div style="height: 10px"></div>
                <table>
                    <tr>
                        <td style="width: 170px">Nama</td>
                        <td style="width: 20px">:</td>
                        <td style="border-bottom: 1px dotted #333;">{{ $data['data_employees']['name'] }}</td>
                    </tr>
                    <tr>
                        <td>No. ID Card</td>
                        <td>:</td>
                        <td>{{ $data['data_employees']['number'] }}</td>
                    </tr>
                    <tr>
                        <td>Bagian/Penempatan</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;">{{ $data['data_employees']['master_positions']['name'] }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Pekerjaan</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;">-</td>
                    </tr>
                </table>

                <div style="height: 10px"></div>
                <label>Untuk melaksanakan kerja lembur :</label>
                <div style="height: 10px"></div>
                <table>
                    <tr>
                        <td style="width: 170px">Hari/Tanggal</td>
                        <td style="width: 20px">:</td>
                        <td style="border-bottom: 1px dotted #333;">{{ \Carbon\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Waktu Mulai/Selesai Pekerjaan</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;">
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
                        <td style="text-align: right">
                            <strong>
                                Jumlah :
                                {{ $data['data_lembur']['hours'] }} Jam
                                {{ ($data['data_lembur']['minutes']>0)?$data['data_lembur']['minutes'].' Menit':null }}
                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <td>Lokasi</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;">Integrated Terminal Dumai</td>
                        {{-- <td>{{ $data['data_employees']['master_locations']['name'] }}</td> --}}
                    </tr>
                    <tr>
                        <td>Biaya Ditanggung Oleh</td>
                        <td>:</td>
                        <td>Perusahaan</td>
                    </tr>

                    <tr>
                        <td>Pekerjaan Dilemburkan</td>
                        <td>:</td>
                        <td style="border-bottom: 1px dotted #333;height: 50px">{{ $data['pekerjaan'] }}</td>
                    </tr>
                </table>

                <div style="height: 20px"></div>
                <table>
                    <tr>
                        <td style="width: 40%">
                            YBS, <br>
                            {{ $data['pengawas2']['master_positions']['name'] }}
                        </td>
                        <td style="width: 20%"></td>
                        <td>
                            Dumai, {{ \Carbon\Carbon::parse(date('d M Y'))->locale('id')->translatedFormat('d F Y') }}
                            <br>
                            Menyetujui <br>
                            Koordinator Lapangan MOR 1
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="height: 0px"></td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td>
                            <div style="height: 100px"></div>
                            <div style="border-bottom: 0.1px solid black;padding-bottom: 5px">
                                {{ $data['data_employees']['name'] }}
                            </div>
                        </td>
                        <td></td>
                        <td>
                            <div style="height: 100px"></div>
                            <div style="border-bottom: 0.1px solid black;padding-bottom: 5px">
                                {{ $data['korlap'] }}
                            </div>
                        </td>
                    </tr>
                </table>

                <div style="height: 20px"></div>
                <table>
                    <tr>
                        <td>
                            <label>SECURITY</label>
                            <table class="table-border table-padding-sm">
                                <tr style="background: rgb(0, 63, 158);color: white;">
                                    <td style="text-align: center">KETERANGAN</td>
                                    <td style="text-align: center">PUKUL</td>
                                    <td style="text-align: center">TANDA TANGAN</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle">JAM MULAI</td>
                                    <td style="text-align: center;height: 30px;vertical-align: middle">
                                        {{ $data['data_lembur']['start_carbon']->format('H:i') }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle">JAM SELESAI</td>
                                    <td style="text-align: center;line-height: 10px;height: 30px;vertical-align: middle">
                                        @php
                                            if ($data['data_lembur']['start_carbon']->isSameDay($data['data_lembur']['end_carbon'])) {
                                                $hasil = $data['data_lembur']['end_carbon']->format('H:i:s');
                                            } else {
                                                $hasil = $data['data_lembur']['end_carbon']->translatedFormat('d/m/Y').'<br>'.$data['data_lembur']['end_carbon']->format('H:i');
                                            }
                                            echo $hasil;
                                        @endphp
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20px"></td>
                        <td>
                            <label>HSE*)</label>
                            <table class="table-border table-padding-sm">
                                <tr style="background: rgb(166, 0, 0);color: white;">
                                    <td style="text-align: center">KETERANGAN</td>
                                    <td style="text-align: center">PUKUL</td>
                                    <td style="text-align: center">TANDA TANGAN</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle">JAM MULAI</td>
                                    <td style="text-align: center;height: 30px;vertical-align: middle">
                                        {{ $data['data_lembur']['start_carbon']->format('H:i') }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle">JAM SELESAI</td>
                                    <td style="text-align: center;line-height: 10px;height: 30px;vertical-align: middle">
                                        @php
                                            if ($data['data_lembur']['start_carbon']->isSameDay($data['data_lembur']['end_carbon'])) {
                                                $hasil = $data['data_lembur']['end_carbon']->format('H:i:s');
                                            } else {
                                                $hasil = $data['data_lembur']['end_carbon']->translatedFormat('d/m/Y').'<br>'.$data['data_lembur']['end_carbon']->format('H:i');
                                            }
                                            echo $hasil;
                                        @endphp
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="width: 20px"></td>
                        <td>*) Apabila memasuki area terbatas/larangan/limit hazard</td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>


</body>
</html>
