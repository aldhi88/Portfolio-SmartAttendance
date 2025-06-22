<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
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
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form class="p-3 bg-light rounded" method="get">

                                <div class="row">
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Bulan</label>
                                            <select wire:model.live="filter.thisMonth" name="month" class="form-control ex-filter">
                                                @foreach ($dt['indoMonthList'] as $key=>$item)
                                                <option value="{{ $key }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <select wire:model.live="filter.thisYear" name="year" wire:model="filter.thisYear" class="form-control ex-filter">
                                                @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                                                    <option value="{{ $i }}">
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md">
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
                                    <div class="col-12 col-md">
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
                                    <div class="col-12 col-md">
                                        <div class="form-group">
                                            <label>Filter Range Tanggal (start - end)</label>
                                            <div>
                                                <div class="input-group">
                                                    <input wire:model.live="filter.start_value" min="{{ $filter['min_start'] }}" max="{{ $filter['max_start'] }}" value="{{ $filter['start_value'] }}" type="date" class="form-control" name="start" />
                                                    <span class="px-1 pt-2">-</span>
                                                    <input wire:model.live="filter.end_value" min="{{ $filter['min_end'] }}" max="{{ $filter['max_end'] }}" value="{{ $filter['end_value'] }}" type="date" class="form-control" name="end" />
                                                </div>
                                                @if ($errors->has('form.from') || $errors->has('form.to'))
                                                    <div class="text-danger">
                                                        <small>{{ $errors->first('form.from') ?: $errors->first('form.to') }}</small>
                                                    </div>
                                                @endif

                                                @error('tgl_range_invalid')
                                                    <div class="text-danger">
                                                        <small>{{ $message }}</small>
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Urutan Data Berdasarkan</label>
                                            <select name="order" wire:model="filter.order" class="form-control">
                                                @foreach ($dt['orderList'] as $key => $item)
                                                <option value="{{$key}}">{{$item}}</option>
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
                                                        <button type="submit" class="btn btn-info btn-block">Tampilkan Data</button>
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

    <div class="row" wire:ignore>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex align-items-stretch">
                        <div class="col-12 col-md-3 mb-2">
                            <div class="bg-soft-secondary p-2 rounded border border-secondary h-100">
                                <i class="fas fa-calendar-alt mr-2"></i>Range Tanggal :
                                <h6 class="py-0 my-0">{{$dt['tglCol'][0]['col_date']}} - {{$dt['tglCol'][count($dt['tglCol'])-1]['col_date']}} {{$thisMonthLabel}} {{$filter['thisYear']}}</h6>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 mb-2">
                            <div class="bg-soft-secondary p-2 rounded border border-secondary h-100">
                                <i class="fas fa-sort-alpha-down mr-2"></i>Urutan Data :
                                <h6 class="py-0 my-0">{{$filter['order_label']??"-"}}</h6>
                            </div>
                        </div>
                        <div class="col-12 col-md mb-2">
                            <div class="bg-soft-secondary p-2 rounded border border-secondary h-100">
                                <i class="fas fa-building mr-2"></i>Perusahaan :
                                <h6 class="py-0 my-0">{{ $filter['org_label']??'Semua'}}</h6>
                            </div>
                        </div>
                        <div class="col-12 col-md mb-2">
                            <div class="bg-soft-secondary p-2 rounded border border-secondary h-100">
                                <i class="fas fa-user-tie mr-2"></i>Jabatan :
                                <h6 class="py-0 my-0">{{$filter['pos_label']??'Semua'}}</h6>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex justify-content-start mb-2">
                                        <div id="lengthContainer"></div>
                                    </div>
                                </div>
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
                                            <th class="text-center" colspan="4">
                                                Akumulasi Waktu (Menit)
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
                                            <th class="text-center">Datang <br> Cepat</th>
                                            <th class="text-center">Datang <br> Lama</th>
                                            <th class="text-center">Pulang <br> Cepat</th>
                                            <th class="text-center">Pulang <br> Lama</th>
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
