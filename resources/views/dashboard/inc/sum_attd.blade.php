<div class="row">

    <div class="col-12 col-md-3">
        <div class="card bg-soft-info border border-info">
            <div class="card-body">
                <div class="media">
                    <div class="media-body overflow-hidden">
                        <p class="text-truncate mb-0">Total Karyawan Aktif</p>
                        <h2 class="mb-0" style="margin-top: 20px">
                            <span id="jlh_karyawan">-</span>
                            <span class="text-rapat lead">Orang</span>
                        </h2>
                        <h6 class="text-rapat lead mt-3">Hari ini :</h6>
                        <h5 id="today"></h5>

                        <hr>
                        <div class="mt-">
                            <a href="{{ route('karyawan.index') }}" class="btn btn-info btn-block btn-lg rounded-0">Semua Karyawan</a>
                        </div>

                    </div>
                </div>
                <div class="position-absolute" style="top: 5px; right: 5px;opacity: 15%;">
                    <i class="fas fa-user-tie text-info" style="font-size: 90px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col">

        <div class="row">

            <div class="col-12 col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Datang Ontime</p>
                                <h4 class="mb-0" id="dtg-ontime">0</h4>
                                <small>hari ini</small>
                            </div>
                            <div class="text-info">
                                <i class="ri-stack-line font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Datang Terlambat</p>
                                <h4 class="mb-0" id="dtg-terlambat">0</h4>
                                <small>hari ini</small>
                            </div>
                            <div class="text-info">
                                <i class="ri-stack-line font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Tidak Absen Datang</p>
                                <h4 class="mb-0" id="dtg-noabsen">0</h4>
                                <small>hari ini</small>
                            </div>
                            <div class="text-info">
                                <i class="ri-stack-line font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-12 col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Pulang Ontime</p>
                                <h4 class="mb-0" id="plg-ontime">0</h4>
                                <small>hari ini</small>
                            </div>
                            <div class="text-info">
                                <i class="ri-stack-line font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Pulang Cepat</p>
                                <h4 class="mb-0" id="plg-cepat">0</h4>
                                <small>hari ini</small>
                            </div>
                            <div class="text-info">
                                <i class="ri-stack-line font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Tidak Absen Pulang</p>
                                <h4 class="mb-0" id="plg-noabsen">0</h4>
                                <small>hari ini</small>
                            </div>
                            <div class="text-info">
                                <i class="ri-stack-line font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
