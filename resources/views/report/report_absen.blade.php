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

                    <form method="GET">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Bulan</label>
                                    <select name="month" id="filter-month" class="form-control filter-external">
                                        @foreach ($dt['indoMonthList'] as $key=>$item)
                                        <option value="{{ $key }}" {{ $key == $month ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select name="year" id="filter-year" class="form-control filter-external">
                                        @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label style="visibility: hidden">Tombol</label> <br>
                                <button type="submit" class="btn btn-primary btn-block">Tampilkan</button>
                            </div>
                            <div class="col-md-2">
                                <label style="visibility: hidden">Tombol</label> <br>
                                <a href="{{ route('report.absen') }}" class="btn btn-warning filter-ex">Reset</a>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <div class="row">
                        <div class="col">

                            <div class="table-responsive mt-2">
                                <table id="myTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center" style="min-width: 10px">No</th>
                                            <th rowspan="2" class="text-center">Nama</th>
                                            <th rowspan="2" class="text-center">Perusahaan</th>
                                            <th rowspan="2" class="text-center">Jabatan</th>
                                            @foreach ($dt['tglCol'] as $item)
                                            <th colspan="2" class="text-center text-nowrap
                                                {{ $item['col_day']=='Sabtu' || $item['col_day']=='Minggu' ? 'bg-soft-danger':null }}
                                                ">
                                                {{ $item['col_day'] }} <br>
                                                {{ $item['col_date'] }}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($dt['tglCol'] as $item)
                                            <th class="text-center
                                                {{ $item['col_day']=='Sabtu' || $item['col_day']=='Minggu' ? 'bg-soft-danger':null }}">
                                                <i class="fas fa-arrow-up text-success"></i>
                                            </th>
                                            <th class="text-center
                                                {{ $item['col_day']=='Sabtu' || $item['col_day']=='Minggu' ? 'bg-soft-danger':null }}">
                                                <i class="fas fa-arrow-down text-danger"></i>
                                            </th>
                                            {{-- <th class="text-center
                                                {{ $item['col_day']=='Sabtu' || $item['col_day']=='Minggu' ? 'bg-soft-danger':null }}">
                                                <i class="fas fa-user-clock text-dark"></i>
                                            </th> --}}
                                            @endforeach
                                        </tr>
                                    </thead>

                                    <thead id="header-filter">
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" class="form-control form-control-sm search-col-dt">
                                            </th>
                                            <th>
                                                <select class="form-control form-control-sm search-col-dt">
                                                    <option value="">Semua</option>
                                                    @foreach ($dt['organization'] as $item)
                                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <th>
                                                <select class="form-control form-control-sm search-col-dt">
                                                    <option value="">Semua</option>
                                                    @foreach ($dt['position'] as $item)
                                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            @foreach ($dt['tglCol'] as $item)
                                            <th class="text-center text-dark bg-{{ $item['col_day']=='Sabtu' || $item['col_day']=='Minggu' ? 'soft-danger':'soft-dark' }}">in</th>
                                            <th class="text-center text-dark bg-{{ $item['col_day']=='Sabtu' || $item['col_day']=='Minggu' ? 'soft-danger':'soft-dark' }}">out</th>
                                            {{-- <th class="text-center text-dark bg-{{ $item['col_day']=='Sabtu' || $item['col_day']=='Minggu' ? 'soft-danger':'soft-dark' }}">status</th> --}}
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
