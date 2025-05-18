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
            <div class="card">
                <div class="card-body">
                    <h4 class="lead">Form Filter</h4>
                    <div class="row">
                        <div class="col-12">
                            <form class="p-3 bg-light rounded" method="get">

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Bulan</label>
                                            <select name="month" wire:model="filter.thisMonth" class="form-control ex-filter">
                                                @foreach ($dt['indoMonthList'] as $key=>$item)
                                                <option value="{{ $key }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
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
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
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
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Nama Karyawan</label>
                                            <input type="text" wire:model="filter.name" name="name" class="form-control ex-filter">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-info">Tampilkan Hasil Filter</button>
                                        <a href="{{ route('report.absen') }}" type="button" class="btn btn-secondary">Reset Filter</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <h4 class="lead mb-0 mt-2">Hasil Filter</h4>
                        </div>
                        <div class="col text-right">
                            <div class="form-group">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-success waves-light waves-effect">
                                        <i class="far fa-file-excel fa-fw"></i>Excel
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger waves-light waves-effect">
                                        <i class="fas fa-file-pdf fa-fw"></i>PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-0">
                    <div class="row mt-3">
                        <div class="col-md-2">
                            <div class="form-group m-0">
                                <label class="m-0">Bulan Tahun :</label>
                                <h5 class="m-0" id="filter-bln-thn">{{ $thisMonthLabel }} {{ $filter['thisYear'] }}</h5>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group m-0">
                                <label class="m-0">Perusahaan :</label>
                                <h5 class="m-0" id="filter-organization">-</h5>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group m-0">
                                <label class="m-0">Jabatan :</label>
                                <h5 class="m-0" id="filter-position">-</h5>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="table-responsive mt-2">
                                <table id="myTable" class="table table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th rowspan="2" class="text-center" style="min-width: 10px">No</th>
                                            <th rowspan="2" class="text-center">Nama</th>
                                            <th rowspan="2" class="text-center">Perusahaan</th>
                                            <th rowspan="2" class="text-center">Jabatan</th>
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
    @include('report.atc.report_data_atc')
</div>
