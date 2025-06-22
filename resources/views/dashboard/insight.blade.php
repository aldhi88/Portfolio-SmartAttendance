<div>
    <canvas id="confetti-canvas"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; pointer-events: none;">
    </canvas>

    @include('dashboard.inc.rank1')


    <div class="row">

        <div class="col-12 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body overflow-hidden">
                            <p class="text-truncate mb-0">Total Karyawan Aktif</p>
                            <h2 class="mb-0">
                                <span id="jlh_karyawan">-</span>
                                <span class="text-rapat lead">Orang</span>
                            </h2>
                            <span class="text-rapat lead">Hari ini :</span>
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

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="text-center">
                            <p class="mb-0">Top 5</p>
                            <h6>Datang Paling Cepat</h6>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table table-hover mb-0 table-centered table-nowrap" id="order1">
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="text-center">
                            <p class="mb-0">Top 5</p>
                            <h6>Datang Paling Lama</h6>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table table-hover mb-0 table-centered table-nowrap" id="order2">
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="text-center">
                            <p class="mb-0">Top 5</p>
                            <h6>Pulang Paling Cepat</h6>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table table-hover mb-0 table-centered table-nowrap" id="order3">
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="text-center">
                            <p class="mb-0">Top 5</p>
                            <h6>Pulang Paling Lama</h6>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table table-hover mb-0 table-centered table-nowrap" id="order4">
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('dashboard.inc.insight_inc')
</div>
