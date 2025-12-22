<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('lembur.lemburCreate') }}" class="btn btn-primary">
                        <i class="fas fa-plus fa-fw"></i> Tambah Data Baru
                    </a>
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
                                            <select name="month" class="form-control ex-filter">
                                                @foreach ($dt['indoMonthList'] as $key => $item)
                                                    <option value="{{ $key }}"
                                                        {{ (int)$key === (int)request('month', now()->month) ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <select name="year" class="form-control ex-filter">
                                                @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                                                    <option value="{{ $i }}"
                                                        {{ (int)$i === (int)request('year', now()->year) ? 'selected' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md">
                                        <div class="form-group mb-0 pb-0">
                                            <label style="visibility: hidden">Action</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <button type="submit" class="btn btn-info btn-block">Tampilkan Data</button>
                                            </div>
                                            <div class="col">
                                                <a href="{{ route('lembur.indexLembur') }}" type="button" class="btn btn-secondary btn-block">Reset</a>
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
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-bordered table-striped" style="width: 100%">
                            <thead>
                            <tr>
                                <th rowspan="2" class="text-center" style="max-width: 5px"></th>
                                <th rowspan="2" class="text-center" width="10">No</th>
                                <th rowspan="2" class="text-center">Karyawan</th>
                                <th rowspan="2" class="text-center">Perusahaan</th>
                                <th rowspan="2" class="text-center">Tanggal</th>
                                <th rowspan="2" class="text-center">Status</th>
                                <th rowspan="2" class="text-center">Pengawas <br> Penandatangan</th>
                                <th class="text-center" colspan="3">Laporan Absensi Lembur</th>
                                <th class="text-center" colspan="2">Ketentuan Absensi</th>
                            </tr>
                            <tr>
                                <th class="text-center">Check In</th>
                                <th class="text-center">Check Out</th>
                                <th class="text-center">Total Jam</th>
                                <th class="text-center">Check In</th>
                                <th class="text-center">Check Out</th>
                            </tr>
                            </thead>

                            <thead id="header-filter">
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>


                </div>
            </div>

        </div>
    </div>
    @include('lembur_vendor.atc.lembur_vendor_data_atc')
</div>
