<div>
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->

                <ul class="metismenu list-unstyled" id="side-menu">
                    @php
                        $rdpPenempatanAdminBadge = 0;
                        $rdpPenempatanKaryawanBadge = 0;
                        $rdpPenempatanPimpinanBadge = 0;
                        $rdpPenempatanHcRegionBadge = 0;
                        $rdpKeluarAdminBadge = 0;
                        $rdpKeluarKaryawanBadge = 0;
                        $rdpKeluarPimpinanBadge = 0;
                        $rdpPerbaikanAdminBadge = 0;
                        $rdpPerbaikanKaryawanBadge = 0;
                        $rdpPerbaikanPimpinanBadge = 0;
                        $rdpPerbaikanVendorBadge = 0;
                        $rdpPengadaanAdminBadge = 0;
                        $rdpPengadaanKaryawanBadge = 0;
                        $rdpPengadaanPimpinanBadge = 0;
                        $rdpPengadaanVendorBadge = 0;

                        try {
                            if (Auth::user()->is_pengawas_rdp || Auth::user()->is_superuser) {
                                $rdpPenempatanAdminBadge = \App\Repositories\RdpKaryawanMasukRepo::countActionable('admin');
                                $rdpKeluarAdminBadge = \App\Repositories\RdpKaryawanKeluarRepo::countActionable('admin');
                                $rdpPerbaikanAdminBadge = \App\Repositories\RdpPerbaikanRepo::countActionable('admin');
                                $rdpPengadaanAdminBadge = \App\Repositories\RdpPengadaanRepo::countActionable('admin');
                            }

                            if (Auth::user()->is_pengawas || Auth::user()->is_karyawan) {
                                $rdpPenempatanKaryawanBadge = \App\Repositories\RdpKaryawanMasukRepo::countActionable('karyawan', Auth::user()->data_employees?->id);
                                $rdpKeluarKaryawanBadge = \App\Repositories\RdpKaryawanKeluarRepo::countActionable('karyawan', Auth::user()->data_employees?->id);
                                $rdpPerbaikanKaryawanBadge = \App\Repositories\RdpPerbaikanRepo::countActionable('karyawan', Auth::user()->data_employees?->id);
                                $rdpPengadaanKaryawanBadge = \App\Repositories\RdpPengadaanRepo::countActionable('karyawan', Auth::user()->data_employees?->id);
                            }

                            if (Auth::user()->is_manajer) {
                                $rdpPenempatanPimpinanBadge = \App\Repositories\RdpKaryawanMasukRepo::countActionable('pimpinan');
                                $rdpKeluarPimpinanBadge = \App\Repositories\RdpKaryawanKeluarRepo::countActionable('pimpinan');
                                $rdpPerbaikanPimpinanBadge = \App\Repositories\RdpPerbaikanRepo::countActionable('pimpinan');
                                $rdpPengadaanPimpinanBadge = \App\Repositories\RdpPengadaanRepo::countActionable('pimpinan');
                            }

                            if (Auth::user()->is_manager_hc_region) {
                                $rdpPenempatanHcRegionBadge = \App\Repositories\RdpKaryawanMasukRepo::countActionable('hc-region');
                            }

                            if (Auth::user()->is_vendor_rdp) {
                                $rdpPerbaikanVendorBadge = \App\Repositories\RdpPerbaikanRepo::countActionable('vendor', Auth::user()->rdp_master_vendors?->id);
                                $rdpPengadaanVendorBadge = \App\Repositories\RdpPengadaanRepo::countActionable('vendor', Auth::user()->rdp_master_vendors?->id);
                            }
                        } catch (\Throwable $e) {
                            $rdpPenempatanAdminBadge = 0;
                            $rdpPenempatanKaryawanBadge = 0;
                            $rdpPenempatanPimpinanBadge = 0;
                            $rdpPenempatanHcRegionBadge = 0;
                            $rdpKeluarAdminBadge = 0;
                            $rdpKeluarKaryawanBadge = 0;
                            $rdpKeluarPimpinanBadge = 0;
                            $rdpPerbaikanAdminBadge = 0;
                            $rdpPerbaikanKaryawanBadge = 0;
                            $rdpPerbaikanPimpinanBadge = 0;
                            $rdpPerbaikanVendorBadge = 0;
                            $rdpPengadaanAdminBadge = 0;
                            $rdpPengadaanKaryawanBadge = 0;
                            $rdpPengadaanPimpinanBadge = 0;
                            $rdpPengadaanVendorBadge = 0;
                        }
                    @endphp

                    {{-- ATTD --}}

                    @if (!Auth::user()->is_vendor_rdp && !Auth::user()->is_manager_hc_region)
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
                        <li class="parent rdp-master-aset rdp-master-cluster rdp-master-rumah rdp-master-vendor rdp-master-manager-hc-region">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-archive-line"></i>
                                <span>Data Master</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child rdp-master-aset"><a href="{{ route('rdp.master.aset.index') }}">Data Aset</a></li>
                                <li class="child rdp-master-cluster"><a href="{{ route('rdp.master.cluster.index') }}">Data Cluster</a></li>
                                <li class="child rdp-master-rumah"><a href="{{ route('rdp.master.rumah.index') }}">Data Unit Rumah</a></li>
                                <li class="child rdp-master-vendor"><a href="{{ route('rdp.master.vendor.index') }}">Data Vendor RDP</a></li>
                                <li class="child rdp-master-manager-hc-region"><a href="{{ route('rdp.master.manager-hc-region.index') }}">Data Manager HC Region</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-home-6-line"></i>
                                @if ($rdpPenempatanAdminBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPenempatanAdminBadge }}</span>
                                @endif
                                <span>Penempatan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child">
                                    <a href="{{ route('rdp.penempatan.izin-penempatan.index') }}">
                                        @if ($rdpPenempatanAdminBadge > 0)
                                            <span class="badge badge-pill badge-success float-right">{{ $rdpPenempatanAdminBadge }}</span>
                                        @endif
                                        Data Pengajuan SIP
                                    </a>
                                </li>
                                <li class="child"><a href="{{ route('rdp.penempatan.izin-penempatan.create') }}">Penempatan Baru</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-home-gear-line"></i>
                                @if ($rdpPerbaikanAdminBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPerbaikanAdminBadge }}</span>
                                @endif
                                <span>Perbaikan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child">
                                    <a href="{{ route('rdp.perbaikan.index') }}">
                                        @if ($rdpPerbaikanAdminBadge > 0)
                                            <span class="badge badge-pill badge-success float-right">{{ $rdpPerbaikanAdminBadge }}</span>
                                        @endif
                                        Data Perbaikan
                                    </a>
                                </li>
                                <li class="child"><a href="{{ route('rdp.perbaikan.create') }}">Ajukan Perbaikan</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-shopping-cart-line"></i>
                                @if ($rdpPengadaanAdminBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPengadaanAdminBadge }}</span>
                                @endif
                                <span>Pengadaan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child">
                                    <a href="{{ route('rdp.pengadaan.index') }}">
                                        @if ($rdpPengadaanAdminBadge > 0)
                                            <span class="badge badge-pill badge-success float-right">{{ $rdpPengadaanAdminBadge }}</span>
                                        @endif
                                        Data Pengadaan
                                    </a>
                                </li>
                                <li class="child"><a href="{{ route('rdp.pengadaan.create') }}">Pengadaan Baru</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-logout-box-r-line"></i>
                                @if ($rdpKeluarAdminBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpKeluarAdminBadge }}</span>
                                @endif
                                <span>Keluar RDP</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child">
                                    <a href="{{ route('rdp.keluar-rdp.izin-keluar.index') }}">
                                        @if ($rdpKeluarAdminBadge > 0)
                                            <span class="badge badge-pill badge-success float-right">{{ $rdpKeluarAdminBadge }}</span>
                                        @endif
                                        Data Izin Keluar
                                    </a>
                                </li>
                                <li class="child"><a href="{{ route('rdp.keluar-rdp.izin-keluar.create') }}">Izin Keluar Baru</a></li>
                            </ul>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-file-list-3-line"></i>
                                <span>Laporan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="{{ route('rdp.laporan.aset-standar.index') }}">Aset Standar RDP</a></li>
                                <li class="child"><a href="{{ route('rdp.laporan.aset-realisasi.index') }}">Aset RDP Realisasi</a></li>
                                <li class="child"><a href="{{ route('rdp.laporan.penempatan.index') }}">Penempatan RDP</a></li>
                            </ul>
                        </li>
                    @endif


                    {{-- Login karyawan --}}
                    @if (Auth::user()->is_pengawas || Auth::user()->is_karyawan)
                        <li>
                            <a href="{{ route('rdp.pengajuan.izin-penempatan.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                @if ($rdpPenempatanKaryawanBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPenempatanKaryawanBadge }}</span>
                                @endif
                                <span>Ajukan SIP</span>
                            </a>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-home-gear-line"></i>
                                @if (($rdpPerbaikanKaryawanBadge + $rdpPengadaanKaryawanBadge) > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPerbaikanKaryawanBadge + $rdpPengadaanKaryawanBadge }}</span>
                                @endif
                                <span>Rumah Saya</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child">
                                    <a href="{{ route('rdp.pengajuan.perbaikan.index') }}">
                                        @if ($rdpPerbaikanKaryawanBadge > 0)
                                            <span class="badge badge-pill badge-success float-right">{{ $rdpPerbaikanKaryawanBadge }}</span>
                                        @endif
                                        Data Perbaikan
                                    </a>
                                </li>
                                <li class="child">
                                    <a href="{{ route('rdp.pengajuan.pengadaan.index') }}">
                                        @if ($rdpPengadaanKaryawanBadge > 0)
                                            <span class="badge badge-pill badge-success float-right">{{ $rdpPengadaanKaryawanBadge }}</span>
                                        @endif
                                        Data Pengadaan
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('rdp.pengajuan.izin-keluar.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                @if ($rdpKeluarKaryawanBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpKeluarKaryawanBadge }}</span>
                                @endif
                                <span>Ajukan Keluar</span>
                            </a>
                        </li>
                    @endif


                    {{-- Login Manajer --}}
                    @if (Auth::user()->is_manajer)
                        <li>
                            <a href="{{ route('rdp.persetujuan.izin-penempatan.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                @if ($rdpPenempatanPimpinanBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPenempatanPimpinanBadge }}</span>
                                @endif
                                <span>Data Pengajuan SIP</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rdp.persetujuan.perbaikan.index') }}" class="waves-effect">
                                <i class="ri-home-gear-line"></i>
                                @if ($rdpPerbaikanPimpinanBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPerbaikanPimpinanBadge }}</span>
                                @endif
                                <span>Data Perbaikan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rdp.persetujuan.pengadaan.index') }}" class="waves-effect">
                                <i class="ri-shopping-cart-line"></i>
                                @if ($rdpPengadaanPimpinanBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPengadaanPimpinanBadge }}</span>
                                @endif
                                <span>Data Pengadaan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rdp.persetujuan.izin-keluar.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                @if ($rdpKeluarPimpinanBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpKeluarPimpinanBadge }}</span>
                                @endif
                                <span>Data Izin Keluar</span>
                            </a>
                        </li>
                        <li class="parent">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-file-list-3-line"></i>
                                <span>Laporan</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="child"><a href="{{ route('rdp.laporan.aset-standar.index') }}">Aset Standar RDP</a></li>
                                <li class="child"><a href="{{ route('rdp.laporan.aset-realisasi.index') }}">Aset RDP Realisasi</a></li>
                                <li class="child"><a href="{{ route('rdp.laporan.penempatan.index') }}">Penempatan RDP</a></li>
                            </ul>
                        </li>
                    @endif

                    {{-- Login Manager HC Region --}}
                    @if (Auth::user()->is_manager_hc_region)
                        <li>
                            <a href="{{ route('rdp.hc-region.izin-penempatan.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                @if ($rdpPenempatanHcRegionBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPenempatanHcRegionBadge }}</span>
                                @endif
                                <span>Data Pengajuan SIP</span>
                            </a>
                        </li>
                    @endif

                    {{-- Login Vendor --}}
                    @if (Auth::user()->is_vendor_rdp)
                        <li>
                            <a href="{{ route('rdp.vendor.perbaikan.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                @if ($rdpPerbaikanVendorBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPerbaikanVendorBadge }}</span>
                                @endif
                                <span>Permintaan Perbaikan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rdp.vendor.pengadaan.index') }}" class="waves-effect">
                                <i class="ri-file-edit-line"></i>
                                @if ($rdpPengadaanVendorBadge > 0)
                                    <span class="badge badge-pill badge-success float-right">{{ $rdpPengadaanVendorBadge }}</span>
                                @endif
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
