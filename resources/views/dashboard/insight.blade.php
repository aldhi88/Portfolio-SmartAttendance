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
                                <span id="count-employee">177</span>
                            </h2>
                            {{-- <h5 class="my-0 text-danger bg-white d-inline px-1" style="min-height: 0px;" id="rank1-name">-</h5> --}}
                            <div class="text-rapat lead" id="rank1-org">Karyawan Aktif</div>
                            {{-- <div class="text-rapat text-white lead" id="rank1-as">-</div> --}}
                        </div>
                    </div>
                    <div class="position-absolute" style="top: 10px; right: 10px;opacity: 15%;">
                        <i class="fas fa-user-tie" style="font-size: 70px;"></i>
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
                                <div class="text-primary">
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
                                <div class="text-primary">
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
                                <div class="text-primary">
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
                                <div class="text-primary">
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
                                <div class="text-primary">
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
                                <div class="text-primary">
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
