<div>
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">Smart Absensi</li>

                    <li>
                        <a href="{{ route('dashboard.index') }}" class="waves-effect">
                            <i class="ri-dashboard-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="parent">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-archive-line"></i>
                            <span>Data Master</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child perusahaan"><a href="{{ route('perusahaan.index') }}">Perusahaan</a></li>
                            <li class="child jabatan"><a href="{{ route('jabatan.index') }}">Jabatan</a></li>
                            <li class="child lokasi"><a href="{{ route('lokasi.index') }}">Lokasi</a></li>
                            <li class="child fungsi"><a href="{{ route('fungsi.index') }}">Fungsi</a></li>
                        </ul>
                    </li>
                    <li class="parent jadwal-kerja karyawan">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-team-line"></i>
                            <span>SDM & Jadwal</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child jadwal-kerja"><a href="{{ route('jadwal-kerja.index') }}">Jadwal Kerja</a></li>
                            <li class="child karyawan "><a href="{{ route('karyawan.index') }}">Karyawan</a></li>
                            <li class="child pengawas "><a href="{{ route('pengawas.index') }}">Pengawas</a></li>
                        </ul>
                    </li>

                    <li class="parent izin">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-calendar-line"></i>
                            <span>Data Libur</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child"><a href="{{ route('izin.indexIzin') }}">Data Pengajuan Izin</a></li>
                            <li class="child"><a href="{{ route('izin.izinCreate') }}">Buat Pengajuan Izin</a></li>
                            <li class="child"><a href="{{ route('merah.indexMerah') }}">Set Tanggal Merah</a></li>
                            {{-- <li class="child "><a href="#">Penjadwalan Karyawan</a></li> --}}
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class=" ri-file-list-3-line"></i>
                            <span>Laporan</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('report.absen') }}">Rekap Absensi</a></li>
                            <li><a href="{{ route('report.rank') }}">Rekap Passing Grade</a></li>
                            <li><a href="{{ route('laporan.indexLogAbsen') }}">Log Absensi</a></li>
                            <li><a href="{{ route('laporan.indexLogGps') }}">Log Lokasi</a></li>
                        </ul>
                    </li>

                    <li class="parent">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-history-line"></i>
                            <span>Lembur</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child"><a href="{{ route('lembur.indexLembur') }}">Data Lembur</a></li>
                            <li class="child"><a href="{{ route('lembur.lemburCreate') }}">Pengajuan Lembur</a></li>
                            <li class="child"><a href="#">Daftar Pekerjaan</a></li>
                            <li class="child"><a href="#">Pejabat Penanda Tangan</a></li>
                            <li class="child"><a href="#">Pembatalan</a></li>
                            <li class="child"><a href="#">Print Surat Lembur</a></li>
                            <li class="child"><a href="#">Rekap Laporan 40 Jam</a></li>
                            <li class="child"><a href="#">Rekap Laporan 40 Jam+</a></li>
                        </ul>
                    </li>

                    <hr>

                    <li class="menu-title">RUMAH DINAS PERTAMINA <br> (masih proses)</li>

                    <li>
                        <a href="#" class="waves-effect">
                            <i class="ri-dashboard-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="parent">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-archive-line"></i>
                            <span>Data Master</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child perusahaan"><a href="#">Data Aset</a></li>
                            <li class="child perusahaan"><a href="#">Penjabat Penanda Tangan</a></li>
                        </ul>
                    </li>
                    <li class="parent">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-archive-line"></i>
                            <span>Data Vendor</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child perusahaan"><a href="#">Daftar Vendor</a></li>
                            <li class="child perusahaan"><a href="#">Buat Vendor Baru</a></li>
                            <li class="child perusahaan"><a href="#">Rekap Daftar Permintaan</a></li>
                        </ul>
                    </li>
                    <li class="parent">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-archive-line"></i>
                            <span>Pengajuan Masuk</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child perusahaan"><a href="#">Daftar Pengajuan Masuk</a></li>
                            <li class="child perusahaan"><a href="#">Pengajuan Baru Keluar</a></li>
                            <li class="child perusahaan"><a href="#">Cetak SIP</a></li>
                        </ul>
                    </li>
                    <li class="parent">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-archive-line"></i>
                            <span>Pengadaan</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child perusahaan"><a href="#">Daftar Pengadaan</a></li>
                            <li class="child perusahaan"><a href="#">Pengadaan Baru</a></li>
                        </ul>
                    </li>
                    <li class="parent">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-archive-line"></i>
                            <span>Perbaikan</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child perusahaan"><a href="#">Daftar Perbaikan</a></li>
                            <li class="child perusahaan"><a href="#">Perbaikan Baru</a></li>
                        </ul>
                    </li>
                    <li class="parent">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-archive-line"></i>
                            <span>Pengajuan Keluar</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child perusahaan"><a href="#">Daftar Pengajuan Keluar</a></li>
                            <li class="child perusahaan"><a href="#">Pengajuan Keluar Baru</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
</div>
