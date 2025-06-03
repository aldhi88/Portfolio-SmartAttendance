<div>
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">Menu</li>

                    <li>
                        <a href="{{ route('dashboard.index') }}" class="waves-effect">
                            <i class="ri-dashboard-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="parent jadwal-kerja karyawan">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-team-line"></i>
                            <span>Manajemen SDM</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child perusahaan"><a href="{{ route('perusahaan.index') }}">Perusahaan</a></li>
                            <li class="child jabatan"><a href="{{ route('jabatan.index') }}">Jabatan</a></li>
                            <li class="child lokasi"><a href="{{ route('lokasi.index') }}">Lokasi</a></li>
                            <li class="child fungsi"><a href="{{ route('fungsi.index') }}">Fungsi</a></li>
                            <li class="child jadwal-kerja"><a href="{{ route('jadwal-kerja.index') }}">Jadwal Kerja</a></li>
                            <li class="child karyawan "><a href="{{ route('karyawan.index') }}">Karyawan</a></li>
                            {{-- <li class="child "><a href="#">Penjadwalan Karyawan</a></li> --}}
                        </ul>
                    </li>

                    <li class="parent izin">
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-calendar-line"></i>
                            <span>Data Libur</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="child izin"><a href="{{ route('izin.indexIzin') }}">Izin</a></li>
                            <li class="child"><a href="{{ route('merah.indexMerah') }}">Tanggal Merah</a></li>
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
                            <li><a href="ecommerce-product-detail.html">Rekap Penilaian</a></li>
                            <li><a href="{{ route('laporan.indexLogAbsen') }}">Log Absensi</a></li>
                            <li><a href="ecommerce-product-detail.html">Log Lokasi</a></li>
                        </ul>
                    </li>

                    {{-- @if (Auth::user()->user_roles->name == 'Super User')

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-settings-3-line"></i>
                            <span>Pengaturan</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('settings.indexAuthorize') }}">Authorization</a></li>
                        </ul>
                    </li>
                    @endif --}}





                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
</div>
