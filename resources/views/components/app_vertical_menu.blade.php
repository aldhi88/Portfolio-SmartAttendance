<div>
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->

                <ul class="metismenu list-unstyled" id="side-menu">

                    {{-- ATTD --}}

                    @if (!Auth::user()->is_vendor_rdp)
                        <li class="menu-title">Smart Absensi</li>
                    @endif

                    @if (Auth::user()->is_pengawas || Auth::user()->is_pengawas_rdp || Auth::user()->is_manajer || Auth::user()->is_superuser)
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
                    @endif

                    @if (Auth::user()->is_karyawan)
                        <li>
                            <a href="{{ route('dashboard.index') }}" class="waves-effect">
                                <i class="ri-dashboard-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->is_vendor)
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
                    @endif


                    {{-- RDP --}}
                    <li class="menu-title">RUMAH DINAS PERTAMINA</li>
                    @if (Auth::user()->is_pengawas_rdp || Auth::user()->is_superuser)
                        <li class="parent rdp-master-aset rdp-master-cluster rdp-master-rumah rdp-master-vendor">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-archive-line"></i>
                                <span>Data Master</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child rdp-master-aset"><a href="{{ route('rdp.master.aset.index') }}">Data Aset</a></li>
                                <li class="child rdp-master-cluster"><a href="{{ route('rdp.master.cluster.index') }}">Data Cluster</a></li>
                                <li class="child rdp-master-rumah"><a href="{{ route('rdp.master.rumah.index') }}">Data Unit Rumah</a></li>
                                <li class="child rdp-master-vendor"><a href="{{ route('rdp.master.vendor.index') }}">Data Vendor RDP</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-home-6-line"></i>
                                <span>Penempatan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="{{ route('rdp.penempatan.izin-penempatan.index') }}">Data Pengajuan SIP</a></li>
                                <li class="child"><a href="{{ route('rdp.penempatan.izin-penempatan.create') }}">Penempatan Baru</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-home-gear-line"></i>
                                <span>Perbaikan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="#">Data Perbaikan</a></li>
                                <li class="child"><a href="#">Ajukan Perbaikan</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-shopping-cart-line"></i>
                                <span>Pengadaan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="#">Data Pengadaan</a></li>
                                <li class="child"><a href="#">Pengadaan Baru</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-logout-box-r-line"></i>
                                <span>Keluar RDP</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="{{ route('rdp.keluar-rdp.izin-keluar.index') }}">Data Izin Keluar</a></li>
                                <li class="child"><a href="{{ route('rdp.keluar-rdp.izin-keluar.create') }}">Izin Keluar Baru</a></li>
                            </ul>
                        </li>
                    @endif


                    {{-- Login karyawan --}}
                    @if (Auth::user()->is_pengawas || Auth::user()->is_karyawan)
                        <li>
                            <a href="{{ route('rdp.pengajuan.izin-penempatan.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                <span>Ajukan SIP</span>
                            </a>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-home-gear-line"></i>
                                <span>Rumah Saya</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="#">Data Perbaikan</a></li>
                                <li class="child"><a href="#">Ajukan Perbaikan</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('rdp.pengajuan.izin-keluar.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                <span>Ajukan Keluar</span>
                            </a>
                        </li>
                    @endif


                    {{-- Login Manajer --}}
                    @if (Auth::user()->is_manajer)
                        <li>
                            <a href="{{ route('rdp.persetujuan.izin-penempatan.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                <span>Data Pengajuan SIP</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="waves-effect">
                                <i class="ri-home-gear-line"></i>
                                <span>Data Perbaikan</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="waves-effect">
                                <i class="ri-shopping-cart-line"></i>
                                <span>Data Pengadaan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rdp.persetujuan.izin-keluar.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                <span>Data Izin Keluar</span>
                            </a>
                        </li>
                    @endif

                    {{-- Login Vendor --}}
                    @if (Auth::user()->is_vendor_rdp)
                        <li>
                            <a href="{{ route('rdp.persetujuan.izin-keluar.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                <span>Permintaan Perbaikan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rdp.persetujuan.izin-keluar.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                <span>Permintaan Pengadaan</span>
                            </a>
                        </li>
                    @endif


                </ul>


            </div>
            <!-- Sidebar -->
        </div>
    </div>
</div>
