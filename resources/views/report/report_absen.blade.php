<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
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
            <div class="row">
                <div class="col">
                    <h4 class="lead mb-0 mt-2"><h4 class="lead">Form Filter</h4></h4>
                </div>
                <div class="col text-right">
                    <div class="form-group">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-sm btn-success waves-light waves-effect" id="export-excel">
                                <i class="far fa-file-excel fa-fw"></i>Excel
                            </button>
                            <button type="button" class="btn btn-sm btn-danger waves-light waves-effect" id="export-pdf">
                                <i class="fas fa-file-pdf fa-fw"></i>PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form class="p-3 bg-light rounded" method="get">

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
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="d-flex justify-content-between mb-2">
                                <div id="lengthContainer"></div>
                            </div>
                            <div class="table-responsive mt-2">
                                <table id="myTable" class="table table-bordered table-striped" style="width: 100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th rowspan="2" class="text-center" style="min-width: 10px">No</th>
                                            <th rowspan="2" class="text-center" id="header-filter">
                                                Nama <br> Perusahaan <br> Jabatan
                                                <input type="text" placeholder="cari nama karyawan" class="form-control form-control-sm text-center search-col-dt">
                                            </th>
                                            {{-- kolom dinamis --}}
                                            @foreach ($dt['tglCol'] as $item)
                                            <th colspan="2" class="text-center text-nowrap
                                                {{ $item['col_day']=='Sabtu' || $item['col_day']=='Minggu' ? 'bg-soft-danger':'bg-soft-secondary' }}
                                                ">
                                                {{ $item['col_day'] }} <br>
                                                {{ $item['col_date'] }}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            {{-- kolom dinamis baris ke 2 --}}
                                            @foreach ($dt['tglCol'] as $item)
                                            <th class="text-center bg-soft-secondary">
                                                <i class="fas fa-angle-double-down"></i>

                                            </th>
                                            <th class="text-center">
                                                <i class="fas fa-angle-double-up"></i>
                                            </th>
                                            @endforeach
                                        </tr>
                                        {{-- <tr id="header-filter">
                                            <th class="text-center"></th>
                                            <th class="text-center">
                                                <input type="text" placeholder="cari nama" class="form-control form-control-sm text-center search-col-dt">
                                            </th>
                                            @foreach ($dt['tglCol'] as $item)
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            @endforeach
                                        </tr> --}}
                                    </thead>
                                    {{-- <thead id="header-filter">
                                        <tr>
                                            <th class="text-center"></th>
                                            <th class="text-center">
                                                <input type="text" placeholder="cari nama" class="form-control form-control-sm text-center search-col-dt">
                                            </th>
                                            @foreach ($dt['tglCol'] as $item)
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            @endforeach
                                        </tr>
                                    </thead> --}}
                                    <tbody></tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    @include('report.atc.report_absen_atc')
</div>
