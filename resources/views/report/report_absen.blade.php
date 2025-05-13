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
                                    <button class="btn btn-danger btn-sm delete-mulitple" id="btnDeleteSelected" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </th>
                                <th class="text-center" style="max-width: 5px"></th>
                                <th class="text-center" style="min-width: 10px">No</th>
                                <th class="text-center" style="max-width: 85px">Status</th>
                                <th class="text-center" style="min-width: 100px">Nama</th>
                                <th class="text-center" style="min-width: 100px">Perusahaan</th>
                                <th class="text-center" style="min-width: 100px">Jabatan</th>
                                <th class="text-center" style="min-width: 100px">Lokasi</th>
                                <th class="text-center" style="min-width: 60px">Fungsi</th>
                            </tr>
                            </thead>

                            <thead id="header-filter">
                                <tr>
                                    <th class="text-center">
                                        <input type="checkbox" class="check-data-all">
                                    </th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Belum Aktif">Belum Aktif</option>
                                            <option value="Tidak Aktif">Tidak Aktif</option>
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        <input type="text" class="form-control form-control-sm text-center search-col-dt">
                                    </th>
                                    <th class="text-center">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            {{-- @foreach ($dt['organization'] as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach --}}
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            {{-- @foreach ($dt['position'] as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach --}}
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            {{-- @foreach ($dt['location'] as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach --}}
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        <select name="" class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            {{-- @foreach ($dt['function'] as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach --}}
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
    {{-- @include('employee.atc.employee_data_atc')
    @include('components.modal.modal_confirm_delete')
    @include('components.modal.modal_confirm_delete_multiple') --}}
</div>
