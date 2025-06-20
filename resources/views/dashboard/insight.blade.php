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
                                    <p class="text-truncate font-size-14 mb-2">Number of Sales</p>
                                    <h4 class="mb-0">1452</h4>
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
                                    <p class="text-truncate font-size-14 mb-2">Number of Sales</p>
                                    <h4 class="mb-0">1452</h4>
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
                                    <p class="text-truncate font-size-14 mb-2">Number of Sales</p>
                                    <h4 class="mb-0">1452</h4>
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
                                    <p class="text-truncate font-size-14 mb-2">Number of Sales</p>
                                    <h4 class="mb-0">1452</h4>
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
                                    <p class="text-truncate font-size-14 mb-2">Number of Sales</p>
                                    <h4 class="mb-0">1452</h4>
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
                                    <p class="text-truncate font-size-14 mb-2">Number of Sales</p>
                                    <h4 class="mb-0">1452</h4>
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

    @include('dashboard.inc.insight_inc')
</div>
