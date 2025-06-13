<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a class="btn btn-primary" href="{{ route('karyawan.create') }}">
                        <i class="fas fa-plus fa-fw"></i> Tambah Data Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row" wire:ignore>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center" style="max-width: 30px">
                                    <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-danger dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-h pt-1"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1" style="">
                                                <a class="dropdown-item btnMultipleProcess" href="javascript:void(0);"
                                                    data-process="set-aktif"
                                                    data-msg="Set Aktif"
                                                >Set Aktif</a>
                                                <a class="dropdown-item btnMultipleProcess" href="javascript:void(0);"
                                                    data-process="set-nonaktif"
                                                    data-msg="Set Tidak Aktif"
                                                >Set Tidak Aktif</a>
                                                {{-- <a class="dropdown-item btnMultipleProcess" href="javascript:void(0);">Set Perusahaan</a>
                                                <a class="dropdown-item btnMultipleProcess" href="javascript:void(0);">Set Jabatan</a>
                                                <a class="dropdown-item btnMultipleProcess" href="javascript:void(0);">Set Lokasi</a>
                                                <a class="dropdown-item btnMultipleProcess" href="javascript:void(0);">Set Fungsi</a> --}}
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="text-center"></th>
                                <th class="text-center">No</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Jadwal</th>
                                <th class="text-center">Perusahaan</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Lokasi</th>
                                <th class="text-center">Fungsi</th>
                            </tr>
                            </thead>

                            <thead id="header-filter">
                                <tr>
                                    <th class="text-center">
                                        <input type="checkbox" class="check-data-all">
                                    </th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center" style="min-width: 110px">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Belum Aktif">Belum Aktif</option>
                                            <option value="Tidak Aktif">Tidak Aktif</option>
                                        </select>
                                    </th>
                                    <th class="text-center" style="min-width: 100px">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            @foreach ($dt['roles'] as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        <input type="text" class="form-control form-control-sm text-center search-col-dt">
                                    </th>
                                    <th class="text-center"></th>
                                    <th class="text-center">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            @foreach ($dt['organization'] as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            @foreach ($dt['position'] as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            @foreach ($dt['location'] as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="text-center" style="min-width: 80px">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            @foreach ($dt['function'] as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @include('employee.atc.employee_data_atc')
    @include('employee.atc.modal_confirm')
</div>
