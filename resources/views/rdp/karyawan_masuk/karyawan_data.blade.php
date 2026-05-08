<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.pengajuan.izin-penempatan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus fa-fw"></i> Tambah Pengajuan
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
                        <table id="myTable" class="table table-bordered table-striped" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nomor SK Mutasi</th>
                                    <th class="text-center">Tanggal SK Mutasi</th>
                                    <th class="text-center">Tanggal Mulai</th>
                                    <th class="text-center">Rumah</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">File SIP</th>
                                </tr>
                            </thead>
                            <thead id="header-filter">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th><input type="text" class="form-control form-control-sm search-col-dt"></th>
                                    <th><input type="text" class="form-control form-control-sm search-col-dt"></th>
                                    <th><input type="text" class="form-control form-control-sm search-col-dt"></th>
                                    <th><input type="text" class="form-control form-control-sm search-col-dt"></th>
                                    <th>
                                        <select class="form-control form-control-sm search-col-dt">
                                            <option value="">Semua</option>
                                            @foreach ($statusList as $status)
                                                <option value="{{ $status }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('rdp.karyawan_masuk.atc.karyawan_data_atc')
    @include('components.modal.modal_confirm_delete')
</div>
