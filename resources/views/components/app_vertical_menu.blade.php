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

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-time-line"></i>
                            <span>Jam Kerja</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="ecommerce-products.html">Shift</a></li>
                            <li><a href="ecommerce-product-detail.html">Lembur</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-folder-user-line"></i>
                            <span>Karyawan</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="ecommerce-products.html">Organisasi</a></li>
                            <li><a href="ecommerce-product-detail.html">Jabatan</a></li>
                            <li><a href="ecommerce-product-detail.html">Lokasi</a></li>
                            <li><a href="ecommerce-product-detail.html">Fungsi</a></li>
                            <li><a href="ecommerce-product-detail.html">Karyawan</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#" class="waves-effect">
                            <i class="ri-fingerprint-line"></i>
                            <span>Mesin Absensi</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class=" ri-file-list-3-line"></i>
                            <span>Laporan</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="ecommerce-product-detail.html">Rekapitulasi</a></li>
                            <li><a href="{{ route('laporan.indexLogAbsen') }}">Log Absensi</a></li>
                            <li><a href="ecommerce-product-detail.html">Log Lokasi</a></li>
                        </ul>
                    </li>



                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
</div>
