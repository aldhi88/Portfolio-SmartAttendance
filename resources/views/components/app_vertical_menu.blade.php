<div>
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->

                @if (Auth::user()->is_pengawas || Auth::user()->is_superuser)

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
                                <li class="child jadwal-kerja"><a href="{{ route('jadwal-kerja.index') }}">Jadwal Kerja</a>
                                </li>
                                <li class="child karyawan "><a href="{{ route('karyawan.index') }}">Karyawan</a></li>
                                <li class="child pengawas "><a href="{{ route('pengawas.index') }}">Pengawas</a></li>
                            </ul>
                        </li>

                        <li class="parent izin">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-calendar-line"></i>
                                <span>Izin & Libur</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="{{ route('izin.indexIzin') }}">Data Pengajuan Izin</a></li>
                                <li class="child"><a href="{{ route('izin.izinCreate') }}">Buat Pengajuan Izin</a></li>
                                <li class="child"><a href="{{ route('merah.indexMerah') }}">Set Tanggal Merah</a></li>
                                {{-- <li class="child "><a href="#">Penjadwalan Karyawan</a></li> --}}
                            </ul>
                        </li>

                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-history-line"></i>
                                <span>Lembur</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="{{ route('lembur.indexLembur') }}">Data Pengajuan Lembur</a></li>
                                <li class="child"><a href="{{ route('lembur.lemburCreate') }}">Tambah Data Lembur</a></li>
                                <li class="child"><a href="{{ route('lembur.rekapBulanan') }}">Laporan Bulanan</a></li>
                                <li class="child"><a href="{{ route('vendor.index') }}">Login Vendor</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class=" ri-file-list-3-line"></i>
                                <span>Laporan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('report.absen') }}">Rekap Absensi</a></li>
                                <li><a href="{{ route('report.rank') }}">Passing Grade Absensi</a></li>
                                <li class="child"><a href="{{ route('lembur.passingGradeLembur') }}">Passing Grade Lembur</a></li>
                                <li class="child"><a href="{{ route('izin.passingGradeCuti') }}">Passing Grade Cuti</a></li>
                                <li><a href="{{ route('laporan.indexLogAbsen') }}">Log Absensi</a></li>
                                <li><a href="{{ route('laporan.indexLogGps') }}">Log Lokasi</a></li>
                            </ul>
                        </li>

                        <hr>
                        {{-- ======================================= RDP ======================================= --}}

                        <li class="menu-title">RUMAH DINAS PERTAMINA <br> (masih proses)</li>

                        <li>
                            <a href="#" class="waves-effect">
                                <i class="ri-dashboard-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="parent rdp-master-aset rdp-master-cluster rdp-master-rumah">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-archive-line"></i>
                                <span>Data Master</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child rdp-master-aset"><a href="{{ route('rdp.master.aset.index') }}">Data Aset</a></li>
                                <li class="child rdp-master-cluster"><a href="{{ route('rdp.master.cluster.index') }}">Data Cluster</a></li>
                                <li class="child rdp-master-rumah"><a href="{{ route('rdp.master.rumah.index') }}">Data Unit Rumah</a></li>
                                <li class="child"><a href="#">Data Vendor RDP</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-archive-line"></i>
                                <span>Penempatan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="#">Karyawan Masuk</a></li>
                                <li class="child"><a href="#">Karyawan Keluar</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-archive-line"></i>
                                <span>Pengajuan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="#">Perbaikan</a></li>
                                <li class="child"><a href="#">Pengadaan</a></li>
                            </ul>
                        </li>

                        {{-- Login karyawan --}}
                        <li>
                            <a href="#" class="waves-effect">
                                <i class="ri-dashboard-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-archive-line"></i>
                                <span>Pengajuan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="#">Penempatan</a></li>
                                <li class="child"><a href="#">Keluar Rumah</a></li>
                                <li class="child"><a href="#">Perbaikan</a></li>
                            </ul>
                        </li>


                        {{-- Login Pimpinan --}}
                        <li>
                            <a href="#" class="waves-effect">
                                <i class="ri-dashboard-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-archive-line"></i>
                                <span>Persetujuan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="#">Karyawan Masuk</a></li>
                                <li class="child"><a href="#">Karyawan Keluar</a></li>
                                <li class="child"><a href="#">Perbaikan</a></li>
                                <li class="child"><a href="#">Pengadaan</a></li>
                            </ul>
                        </li>

                        {{-- Login Vendor --}}
                        <li>
                            <a href="#" class="waves-effect">
                                <i class="ri-dashboard-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-archive-line"></i>
                                <span>Permintaan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="#">Perbaikan</a></li>
                                <li class="child"><a href="#">Pengadaan</a></li>
                            </ul>
                        </li>


                    </ul>
                @endif


                @if (Auth::user()->is_vendor)
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title">Smart Absensi</li>

                        <li>
                            <a href="{{ route('lembur-vendor.indexLembur') }}" class="waves-effect">
                                <i class="ri-file-line"></i>
                                <span>Data Pengajuan Lembur</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lembur-vendor.rekapBulanan') }}" class="waves-effect">
                                <i class="ri-file-line"></i>
                                <span>Laporan Data Lembur</span>
                            </a>
                        </li>

                    </ul>
                @endif
            </div>
            <!-- Sidebar -->
        </div>
    </div>
</div>
