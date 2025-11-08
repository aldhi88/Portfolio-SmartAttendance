{{-- resources/views/privacy.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="robots" content="noindex,nofollow">
    <title>Kebijakan Privasi — {{ $appName ?? 'Smart ITD' }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-MecoB4Z7mH2pGm6sQJ8n7v3TnV6a2wq2pVQm2iZb8QvQf2ZQ0X5p8z9QdW4Gk8J" crossorigin="anonymous">
    <style>
        body {
            line-height: 1.6;
        }

        .toc a {
            text-decoration: none;
        }

        h1,
        h2 {
            scroll-margin-top: 80px;
        }

        .badge-perm {
            font-size: 90%;
        }

        .muted {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light bg-light border-bottom">
        <div class="container">
            <span class="navbar-brand mb-0 h1">{{ $appName ?? 'Smart ITD' }}</span>
            <span class="muted small">Terakhir diperbarui:
                {{ $lastUpdated ?? now()->format('d M Y') }}
            </span>
        </div>
    </nav>

    <main class="container py-4">
        <div class="mb-4">
            <h1 class="h3">Kebijakan Privasi</h1>
            <p class="text-muted mb-0">
                Dokumen ini menjelaskan bagaimana {{ $companyName ?? 'Altekno' }}
                (“kami”) mengumpulkan, menggunakan, dan melindungi data pribadi Anda saat menggunakan
                aplikasi <strong>{{ $appName ?? 'Smart ITD' }}</strong>
                (selanjutnya disebut “Aplikasi”).
            </p>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h5">Ringkasan</h2>
                <p>
                    Aplikasi mencatat lokasi Anda <strong>hanya saat berada di dalam area kerja yang telah
                        ditentukan</strong>
                    (berdasarkan radius/geofence dari server). Pencatatan berjalan oleh layanan latar (foreground
                    service)
                    yang aktif tiap <strong>±15 menit</strong>. Jika di luar area, lokasi <strong>tidak</strong>
                    disimpan.
                    Tujuan utama: <strong>validasi kehadiran kerja</strong> dan bukti klaim apabila absensi manual tidak
                    terekam.
                </p>
                <div>
                    <span class="badge badge-info badge-perm">Notifikasi</span>
                    <span class="badge badge-info badge-perm">Foreground Location</span>
                    <span class="badge badge-info badge-perm">Background Location</span>
                </div>
            </div>
        </div>

        <div class="row">
            <aside class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header py-2">Daftar Isi</div>
                    <div class="list-group list-group-flush toc">
                        <a href="#data-dikumpulkan" class="list-group-item list-group-item-action">1. Data yang
                            Dikumpulkan</a>
                        <a href="#dasar-hukum" class="list-group-item list-group-item-action">2. Dasar Hukum
                            Pemrosesan</a>
                        <a href="#cara-penggunaan" class="list-group-item list-group-item-action">3. Cara Kami
                            Menggunakan Data</a>
                        <a href="#izin-akses" class="list-group-item list-group-item-action">4. Izin Akses & Perilaku
                            Layanan</a>
                        <a href="#penyimpanan-retensi" class="list-group-item list-group-item-action">5. Penyimpanan &
                            Retensi</a>
                        <a href="#berbagi-data" class="list-group-item list-group-item-action">6. Berbagi Data</a>
                        <a href="#keamanan" class="list-group-item list-group-item-action">7. Keamanan</a>
                        <a href="#hak-pengguna" class="list-group-item list-group-item-action">8. Hak Anda</a>
                        <a href="#anak" class="list-group-item list-group-item-action">9. Anak di Bawah Umur</a>
                        <a href="#perubahan" class="list-group-item list-group-item-action">10. Perubahan Kebijakan</a>
                        <a href="#kontak" class="list-group-item list-group-item-action">11. Kontak</a>
                    </div>
                </div>
            </aside>

            <section class="col-lg-8">
                <section id="data-dikumpulkan" class="mb-4">
                    <h2 class="h5">1. Data yang Dikumpulkan</h2>
                    <ul>
                        <li><strong>Data Lokasi</strong> (GPS/Network) untuk menentukan apakah Anda berada di area
                            kerja.</li>
                        <li><strong>Identitas akun internal</strong> (ID karyawan/username) untuk mengaitkan catatan
                            kehadiran.</li>
                        <li><strong>Data perangkat terbatas</strong> seperti versi aplikasi & sistem operasi untuk
                            keandalan.</li>
                        <li><strong>Cookie/session</strong> bila Anda mengakses fitur melalui WebView untuk keperluan
                            login.</li>
                    </ul>
                    <p class="mb-0"><em>Kami tidak mengumpulkan kontak, foto, mikrofon, atau file pribadi Anda.</em>
                    </p>
                </section>

                <section id="dasar-hukum" class="mb-4">
                    <h2 class="h5">2. Dasar Hukum Pemrosesan</h2>
                    <p>
                        Pemrosesan dilakukan atas dasar <strong>kepentingan sah</strong> operasional perusahaan
                        untuk administrasi kehadiran dan keselamatan kerja, serta <strong>persetujuan Anda</strong>
                        terhadap izin akses
                        yang diberikan di perangkat.
                    </p>
                </section>

                <section id="cara-penggunaan" class="mb-4">
                    <h2 class="h5">3. Cara Kami Menggunakan Data</h2>
                    <ul>
                        <li>Validasi kehadiran dan pencatatan aktivitas di area kerja.</li>
                        <li>Audit internal dan penanganan klaim kehadiran.</li>
                        <li>Peningkatan keandalan layanan (mis. penyesuaian radius, diagnosis error).</li>
                    </ul>
                </section>

                <section id="izin-akses" class="mb-4">
                    <h2 class="h5">4. Izin Akses & Perilaku Layanan</h2>
                    <ul>
                        <li><strong>Notifikasi</strong> — memberi tahu pengguna saat pemeriksaan lokasi
                            berlangsung/berhasil/gagal.</li>
                        <li><strong>Lokasi (Foreground)</strong> — diperlukan saat aplikasi aktif untuk akurasi posisi.
                        </li>
                        <li><strong>Lokasi Latar (Background)</strong> — layanan berjalan tiap ±15 menit untuk memeriksa
                            apakah Anda berada di dalam radius area kerja. Jika <em>di dalam</em>, lokasi dicatat &
                            dikirim
                            aman ke server; jika <em>di luar</em>, tidak disimpan.</li>
                    </ul>
                    <p class="mb-0">
                        Aplikasi menampilkan <strong>prominent disclosure</strong> sebelum meminta izin. Tanpa pemberian
                        semua izin,
                        fitur utama tidak dapat digunakan.
                    </p>
                </section>

                <section id="penyimpanan-retensi" class="mb-4">
                    <h2 class="h5">5. Penyimpanan & Retensi</h2>
                    <p>
                        Data disimpan di server {{ $siteUrl ?? 'https://smartitd.com' }} yang dikelola oleh
                        {{ $companyName ?? 'Altekno' }}.
                        Retensi mengikuti kebijakan internal; catatan kehadiran disimpan selama diperlukan untuk tujuan
                        operasional
                        dan kepatuhan, kemudian dihapus/di-anonimkan secara berkala.
                    </p>
                </section>

                <section id="berbagi-data" class="mb-4">
                    <h2 class="h5">6. Berbagi Data</h2>
                    <p>
                        Kami <strong>tidak menjual</strong> data pribadi Anda. Data hanya dibagikan kepada pihak
                        internal yang berwenang
                        atau penyedia layanan yang mengikat perjanjian kerahasiaan, bila diperlukan untuk pengoperasian
                        Aplikasi.
                    </p>
                </section>

                <section id="keamanan" class="mb-4">
                    <h2 class="h5">7. Keamanan</h2>
                    <p>
                        Kami menerapkan kontrol keamanan yang wajar (enkripsi saat transit, kontrol akses berbasis
                        peran,
                        dan audit internal). Namun, tidak ada metode transmisi atau penyimpanan yang 100% aman.
                    </p>
                </section>

                <section id="hak-pengguna" class="mb-4">
                    <h2 class="h5">8. Hak Anda</h2>
                    <ul>
                        <li>Mengakses atau meminta salinan data pribadi tertentu.</li>
                        <li>Meminta koreksi atau penghapusan sesuai kebijakan & peraturan yang berlaku.</li>
                        <li>Menarik persetujuan izin di pengaturan perangkat (dampaknya fitur utama tidak berfungsi).
                        </li>
                    </ul>
                </section>

                <section id="anak" class="mb-4">
                    <h2 class="h5">9. Anak di Bawah Umur</h2>
                    <p>
                        Aplikasi ditujukan untuk pegawai/dewasa dalam lingkungan kerja dan tidak ditujukan untuk
                        anak-anak.
                    </p>
                </section>

                <section id="perubahan" class="mb-4">
                    <h2 class="h5">10. Perubahan Kebijakan</h2>
                    <p>
                        Kami dapat memperbarui kebijakan ini dari waktu ke waktu. Perubahan signifikan akan
                        diinformasikan
                        melalui aplikasi atau situs.
                    </p>
                </section>

                <section id="kontak" class="mb-5">
                    <h2 class="h5">11. Kontak</h2>
                    <p class="mb-1">{{ $companyName ?? 'Altekno' }}</p>
                    <p class="mb-1">
                        Email: <a href="mailto:{{ $contactEmail ?? 'altekno.co.id@gmail.com' }}">
                            {{ $contactEmail ?? 'altekno.co.id@gmail.com' }}</a>
                    </p>
                    @isset($siteUrl)
                        <p class="mb-0">Situs: <a href="{{ $siteUrl }}">{{ $siteUrl }}</a></p>
                    @endisset
                </section>
            </section>
        </div>
    </main>

    <footer class="border-top py-3">
        <div class="container small text-muted">
            © {{ date('Y') }} {{ $companyName ?? 'Altekno' }}. Seluruh hak cipta.
        </div>
    </footer>
</body>

</html>
