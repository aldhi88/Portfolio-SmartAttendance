<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <canvas id="confetti-canvas" style="position:fixed; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:9999;"></canvas>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                {{-- <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a class="btn btn-primary" href="{{ route('karyawan.create') }}">
                        <i class="fas fa-plus fa-fw"></i> Tambah Data Baru
                    </a>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h4 class="lead">Form Filter</h4>
            <div class="card">
                <div class="card-body">
                    <form class="rounded" method="get">

                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Bulan</label>
                                    <select name="month" wire:model="filter.thisMonth" class="form-control ex-filter">
                                        @foreach ($dt['indoMonthList'] as $key=>$item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select name="year" wire:model="filter.thisYear" class="form-control ex-filter">
                                        @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                                            <option value="{{ $i }}">
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Perusahaan</label>
                                    <select name="master_organization_id" wire:model="filter.master_organization_id" class="form-control ex-filter">
                                        <option value="">Semua</option>
                                        @foreach ($dt['organization'] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <select name="master_position_id" wire:model="filter.master_position_id" class="form-control ex-filter">
                                        <option value="">Semua</option>
                                        @foreach ($dt['position'] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-0 pb-0">
                                            <label style="visibility: hidden">Action</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <button type="submit" class="btn btn-info btn-block">Filter</button>
                                            </div>
                                            <div class="col">
                                                <a href="{{ route('report.absen') }}" type="button" class="btn btn-secondary btn-block">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border border-danger">
                <div class="card-body position-relative">
                    <div class="media">
                        <div class="media-body overflow-hidden pr-2">
                            <p class="text-truncate font-size-14 mb-2">Karyawan Disiplin</p>
                            <h1 class="mb-1">
                                <i class="fas fa-coins text-danger" style="scale: 0.5"></i>
                                <span id="rank1-point">-</span>
                            </h1>
                            <h5 class="mb-1 mt-2" style="min-height: 0px" id="rank1-name">-</h5>
                            <div class="text-muted text-rapat" id="rank1-org">-</div>
                            <div class="text-muted text-rapat" id="rank1-as">-</div>
                        </div>
                    </div>
                    <div class="position-absolute" style="top: -1px; right: 1px;">
                        <i class="fas fa-medal text-danger" style="font-size: 75px;"></i>
                    </div>
                </div>

            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md border-right border-secondary">
                            <div class="media text-center">
                                <div class="media-body overflow-hidden">
                                    <div class="d-flex justify-content-center">
                                        <div class="d-flex align-items-center justify-content-center bg-soft-primary rounded-circle" style="width: 84px; height: 84px;">
                                            <i class="fas fa-business-time text-primary" style="font-size: 35px;"></i>
                                        </div>
                                    </div>
                                    <h4 class="mt-2 mb-0"><span id="rank1-day-work">-</span> <small>hari kerja</small></h4>
                                    <p class="text-truncate font-size-14 mb-0 pb-0">Dalam Sebulan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md border-right border-secondary">
                            <div class="media text-center">
                                <div class="media-body overflow-hidden">
                                    <div class="d-flex justify-content-center">
                                        <div class="d-flex align-items-center justify-content-center bg-soft-success rounded-circle" style="width: 84px; height: 84px;">
                                            <i class="fas fa-calendar-check text-success" style="font-size: 35px;"></i>
                                        </div>
                                    </div>
                                    <h4 class="mt-2 mb-0"><span id="rank1-hadir">-</span> <small>hari</small></h4>
                                    <p class="text-truncate font-size-14 mb-0 pb-0">Kehadiran</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md border-right border-secondary">
                            <div class="media text-center">
                                <div class="media-body overflow-hidden">
                                    <div class="d-flex justify-content-center">
                                        <div class="d-flex align-items-center justify-content-center bg-soft-warning rounded-circle" style="width: 84px; height: 84px;">
                                            <i class="fas fa-calendar-times text-warning" style="font-size: 35px;"></i>
                                        </div>
                                    </div>
                                    <h4 class="mt-2 mb-0"><span id="rank1-noabsen">-</span> <small>kali</small></h4>
                                    <p class="text-truncate font-size-14 mb-0 pb-0">Tidak Absen</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md">
                            <div class="media text-center">
                                <div class="media-body overflow-hidden">
                                    <div class="d-flex justify-content-center">
                                        <div class="d-flex align-items-center justify-content-center bg-soft-info rounded-circle" style="width: 84px; height: 84px;">
                                            <i class="fas fa-clock text-info" style="font-size: 35px;"></i>
                                        </div>
                                    </div>
                                    <h4 class="mt-2 mb-0"><span id="rank1-loyal">-</span> <small>jam</small></h4>
                                    <p class="text-truncate font-size-14 mb-0 pb-0">Loyal Time</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h4 class="lead">Detail Grade</h4>

                    <div class="row">
                        <div class="col">
                            <div class="table-responsive mt-2">
                                <table id="myTable" class="table table-bordered table-striped" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center" style="min-width: 10px">Rank</th>
                                            <th rowspan="2" class="text-center" id="header-filter">
                                                Nama <br> Perusahaan <br> Jabatan
                                                <input type="text" placeholder="cari nama karyawan" class="form-control form-control-sm text-center search-col-dt">
                                            </th>
                                            <th rowspan="2" class="text-center">Jumlah <br> Hari <br> Kerja</th>
                                            <th rowspan="2" class="text-center">Tidak <br> Absen</th>
                                            <th rowspan="2" class="text-center">Loyal <br> Time</th>
                                            <th colspan="3" class="text-center">Akumulasi</th>
                                            <th rowspan="2" class="text-center">Total <br> Poin</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Kehadiran</th>
                                            <th class="text-center">Izin</th>
                                            <th class="text-center">Alpa</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    @include('report.atc.report_rank_atc')
</div>
