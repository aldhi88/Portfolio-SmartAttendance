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
            margin: 1cm 2cm 2cm 2cm;
            font-size: 14px !important;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 88%;
            height: 40px;
            overflow: hidden;
        }

        .footer-inner {
            width: 100%;
            height: 100%;
            background: #c00600;
            border-top-right-radius: 80px;
            /* transform: skewX(-35deg); */
            /* transform-origin: bottom left; */
        }
    </style>
</head>

<body>

    <table>
        <tr>
            <td style="text-align: right">
                <div>
                    <img src="{{ public_path('assets/images/logo-dark.png') }}" width="150" alt=""
                        style="filter: grayscale(100%);">
                </div>
            </td>
        </tr>
    </table>

    <div class="content">
        <span>
            Medan, {{ \Illuminate\Support\Carbon::parse($item->tanggal_sip_surat ?? now())->translatedFormat('d F Y') }} <br>
            No. {{ $item->nomor_sip_surat ?: '-' }}
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
            Bapak {{"Nama Karywan"}}-{{"nomor pekerja/nopek"}} <br>
            {{"jabatan"}}
            Integrated Terminal Dumai <br>
            di <br>
            Tempat <br>
        </span>

    </div>




    <div class="footer">
        <table style="font-size: 8px">
            <tr>
                <td style="width: 70%"></td>
                <td>tes</td>
            </tr>
        </table>
        <div class="footer-inner"></div>
    </div>
</body>

</html>


{{-- backup code untuk dipakai nanti --}}
{{-- <div style="margin-top: 40px; width: 260px; text-align: center;">
    <div>{{ \App\Repositories\RdpManagerAccountRepo::getPrintRoleName($managerHcRegion) ?? 'Manager HC Region' }}</div>
    @php
        $ttdPath = $managerHcRegion?->ttd
            ? storage_path('app/public/' . \App\Repositories\RdpManagerAccountRepo::FILE_DIR_TTD . '/' . $managerHcRegion->ttd)
            : null;
    @endphp
    <div style="height: 80px; margin: 12px 0;">
        @if ($ttdPath && file_exists($ttdPath))
            <img src="{{ $ttdPath }}" alt="Tanda tangan" style="max-height: 80px; max-width: 180px;">
        @endif
    </div>
    <strong>{{ $managerHcRegion?->nickname ?? '-' }}</strong>
</div> --}}
