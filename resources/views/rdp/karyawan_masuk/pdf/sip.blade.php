<!doctype html>
<html lang="id">

<head>
    <title>{{ uniqId() }}</title>
    <meta charset="utf-8">
    @include('lembur.pdf.style')
    <style>
        /* @page {
            margin: 1.5cm 2cm 0 2cm;
        } */

        body {
            margin: 2cm 2cm 2cm 2cm;
            font-size: 14px !important;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 88%;
            height: 100px;
            overflow: hidden;
        }

        .footer-inner {
            width: 100%;
            height: 100%;
            background: #c00600;
            border-top-right-radius: 80px;
        }
    </style>
</head>

<body>
    <div style="position: fixed; top: 25px; right: 2cm; z-index: 9999; text-align: right;">
        <img
            src="{{ public_path('assets/images/logo-dark.png') }}"
            width="150"
            style="filter: grayscale(100%); display: block;"
            alt="">
    </div>

    <div class="content">
        <span>
            Medan, {{ $sip['tanggal'] }} <br>
            No. {{ $sip['nomor'] }}
        </span>

        <div style="height: 15px"></div>

        <span>
            <table>
                <tr>
                    <td>Lampiran</td>
                    <td style="width: 10px">:</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td style="width: 10px">:</td>
                    <td><strong>Tata Tertib Penghuni Rumah Dinas Perusahaan</strong></td>
                </tr>
            </table>
        </span>

        <div style="height: 25px"></div>

        <span>
            Yang Terhormat, <br>
            Bapak {{ $sip['nama_karyawan'] }}-{{ $sip['nopek'] }} <br>
            {{ $sip['jabatan'] }}
            Integrated Terminal Dumai <br>
            di <br>
            Tempat <br>
        </span>

        <div style="height: 25px"></div>

        <span>
            <div style="text-align: justify">
                Dengan ini diberitahukan bahwa, telah ditunjuk Rumah Dinas Perusahaan Komplek Perumahan Pertamina Bukit Datuk {{ $sip['rumah'] }}, sebagai tempat tinggal Saudara dengan ketentuan sebagai berikut :
            </div>
        </span>

        <div style="height: 10px"></div>

        <span>
            <table style="width: 100%; border-collapse: separate;border-spacing: 0 10px">
                <tr>
                    <td style="text-align: right">1.</td>
                    <td style="width: 20px"></td>
                    <td style="text-align: justify">
                        Rumah dinas tersebut ditunjuk untuk Saudara huni dalam jangka waktu selama Saudara menjalankan tugas sebagai Pekerja PT Pertamina Patra Niaga Regional Sumbagut.
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">2.</td>
                    <td style="width: 20px"></td>
                    <td style="text-align: justify">
                        Jika Saudara menolak atau tidak menempati Rumah Dinas yang telah ditunjuk oleh Perusahaan, maka Saudara dianggap menolak fasilitas yang telah disediakan Perusahaan dan sebagai konsekwensi dari penolakan dimaksud adalah :
                        <table>
                            <tr>
                                <td style="text-align: right;width: 5px">a.</td>
                                <td style="width: 10px"></td>
                                <td>Saudara didaftarkan kedalam urutan perioritas terakhir.</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;width: 5px">b.</td>
                                <td style="width: 10px"></td>
                                <td>Semua biaya akibat penolakan penempatan RDP menjadi beban pribadi.</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;width: 5px">c.</td>
                                <td style="width: 10px"></td>
                                <td>Rumah Dinas tidak ditempati harus dikembalikan kepada Perusahaan selambat-lambatnya dalam waktu 3 (tiga) bulan setelah Surat Izin Penempatan (SIP) ditanda tangani.</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">3.</td>
                    <td style="width: 20px"></td>
                    <td style="text-align: justify">
                        Setiap adanya tambahan penghuni, harus dilaporkan ke Perusahaan Cq. Area HC Sumbagut untuk memperoleh izin terlebih dahulu.
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">4.</td>
                    <td style="width: 20px"></td>
                    <td style="text-align: justify">
                        Saudara diwajibkan memelihara rumah dan halaman rumah dengan baik dan setiap kerusakan yang timbul akibat kelalaian dan kesengajaan Saudara atau keluarga adalah menjadi tanggung jawab Saudara, biaya atas kerusakan atau penggantian atas kerusakan tersebut termasuk perlengkapan rumah antara lain perabot inventaris, panel listrik, anak kunci dan lain-lain adalah menjadi beban Saudara.
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">5.</td>
                    <td style="width: 20px"></td>
                    <td style="text-align: justify">
                        Kerusakan yang timbul pada rumah, kerangan air, listrik dan lain-lainnya harus segera dilaporkan kepada Perusahaan/melalui Fungsi Asset Operation Sumbagut untuk perbaikan.
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">6.</td>
                    <td style="width: 20px"></td>
                    <td style="text-align: justify">
                        Pemakaian listrik dan air PDAM diupayakan sehemat mungkin dengan mempergunakan peralatan listrik seperlunya, mematikan lampu yang tidak dipergunakan dan pemakaian air PDAM diupayakan sehemat mungkin dan sering memantau kran atau closet (wc).
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">7.</td>
                    <td style="width: 20px"></td>
                    <td style="text-align: justify">
                        Tidak dibenarkan menanam tumbuh-tumbuhan yang berumpun misalnya tebu, pisang, bambu dan lain-lain untuk mencegah bersarangnya nyamuk, demikian pula tidak dibenarkan membuat kolam renang disekitar halaman rumah.
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">8.</td>
                    <td style="width: 20px"></td>
                    <td style="text-align: justify">
                        Tidak diijinkan memelihara ternak baik dihalaman rumah maupun di halaman lain dilingkungan komplek atau rumah Perusahaan.
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">9.</td>
                    <td style="width: 20px"></td>
                    <td style="text-align: justify">
                        Tidak dibenarkan merubah/menambah bangunan dan pekarangan yang mengakibatkan terganggunya keindahan atau perubahan aliran listrik dan lain-lain tanpa ijin tertulis dari Perusahaan.
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">10.</td>
                    <td style="width: 20px"></td>
                    <td style="text-align: justify">
                        Apabila sewaktu-waktu hubungan kerja Saudara dengan Perusahaan terputus, maka Saudara diwajibkan segera menyerahkan rumah beserta perabotnya kepada Perusahaan sesuai dengan ketentuan yang berlaku.
                    </td>
                </tr>
            </table>
        </span>

        <div style="height: 10px"></div>

        <span>
            <div>
                Apabila Saudara menyetujui ketentuan-ketentuan tersebut diatas harap Saudara membubuhkan tanda tangan pada surat Tata Tertib Penghuni Rumah Dinas dan mengembalikannya kepada kami.
            </div>
        </span>

        <div style="height: 50px"></div>
        <span>
            <table>
                <tr>
                    <td style="width: 50%">Menyetujui syarat tersebut diatas,</td>
                    <td style="width: 15%"></td>
                    <td>{{ $sip['manager_role'] }}</td>
                </tr>
                <tr>
                    <td>
                        @if ($sip['employee_ttd_path'])
                            <img src="{{ $sip['employee_ttd_path'] }}" style="max-height: 70px; max-width: 180px;margin-top: 10px" alt="">
                        @endif
                    </td>
                    <td style="height: 80px"></td>
                    <td>
                        @if ($sip['manager_ttd_path'])
                            <img src="{{ $sip['manager_ttd_path'] }}" style="max-height: 70px; max-width: 180px;margin-top: 10px" alt="">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>{{ $sip['nama_karyawan'] }}</strong></td>
                    <td></td>
                    <td><strong>{{ $sip['manager_name'] }}</strong></td>
                </tr>
            </table>
        </span>

        <div style="page-break-before: always;"></div>
        <div style="height: 20px"></div>

        <span>
            <div style="text-align: center">
                <div style="font-weight: bold; font-size: 18px">SURAT IJIN PENGHUNI</div>
                <div style="font-size: 18px; text-decoration: underline">RUMAH DINAS PERUSAHAAN</div>
                <div>No: {{ $sip['nomor'] }}</div>
            </div>
        </span>

        <div style="height: 40px"></div>

        <span>
            Yang bertanda tangan dibawah ini atas nama Perusahaan memberi ijin kepada:
        </span>

        <div style="height: 15px"></div>

        <span>
            <table>
                <tr>
                    <td style="width: 180px">Nama</td>
                    <td style="width: 10px">:</td>
                    <td>{{ $sip['nama_karyawan'] }}</td>
                </tr>
                <tr>
                    <td>No.Pekerja</td>
                    <td>:</td>
                    <td>{{ $sip['nopek'] }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $sip['jabatan'] }}</td>
                </tr>
                <tr>
                    <td>Bagian/lokasi</td>
                    <td>:</td>
                    <td>{{ $sip['bagian_lokasi'] }}</td>
                </tr>
            </table>
        </span>

        <div style="height: 25px"></div>

        <span>
            Untuk menempati Rumah Dinas :
        </span>

        <div style="height: 15px"></div>

        <span>
            <table>
                <tr>
                    <td style="width: 180px">Alamat</td>
                    <td style="width: 10px">:</td>
                    <td>Rumah Dinas Perusahaan Komplek Perumahan Pertamina Bukit Datuk</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>{{ $sip['rumah'] }}</td>
                </tr>
            </table>
        </span>

        <div style="height: 15px"></div>

        <span>
            <table>
                <tr>
                    <td style="width: 180px">Tmt.</td>
                    <td style="width: 10px">:</td>
                    <td>{{ $sip['tanggal_mulai'] }}</td>
                </tr>
            </table>
        </span>

        <div style="height: 20px"></div>

        <span>Ketentuan :</span>

        <div style="height: 15px"></div>

        <span>
            <table style="width: 100%; border-collapse: separate;border-spacing: 0 15px">
                <tr>
                    <td style="width: 25px">1.</td>
                    <td style="text-align: justify">
                        Diharuskan mematuhi <em>“Surat Tata Tertib Penghuni Rumah Dinas Perusahaan” (Terlampir)</em>
                    </td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td style="text-align: justify">
                        Untuk kepentingan Perusahaan, Surat Ijin ini sewaktu-waktu dapat berubah.
                    </td>
                </tr>
            </table>
        </span>

        <div style="height: 20px"></div>

        <span>
            Demikian Surat Ijin Penghuni ini dibuat, untuk dipergunakan seperlunya.
        </span>

        <div style="height: 45px"></div>

        <span>
            Medan, {{ $sip['bulan_tahun'] }} <br>
            {{ $sip['manager_role'] }}
        </span>

        <div style="height: 80px">
            @if ($sip['manager_ttd_path'])
                <img src="{{ $sip['manager_ttd_path'] }}" style="max-height: 70px; max-width: 180px;margin-top: 10px" alt="">
            @endif
        </div>

        <span>
            <strong>{{ $sip['manager_name'] }}</strong>
        </span>

        <div style="height: 55px"></div>

        <span>
            Tembusan : <br>
            1. Area Manager Asset Operation Sumbagut <br>
            2. Integrated Terminal Manager Dumai
        </span>

    </div>

    <div class="footer">
        <table style="font-size: 8px">
            <tr>
                <td style="width: 70%"></td>
                <td>
                    MOR I Medan <br>
                    Jl. Yos Sudarso 8-10, Kelurahan Silasas Kecamatan Medan Barat <br>
                    Sumatera Utara 20114 <br>
                    Telphone F 061 - 4556659 <br>
                    www.pertamina.com
                </td>
            </tr>
        </table>
        <div class="footer-inner"></div>
    </div>
</body>

</html>
