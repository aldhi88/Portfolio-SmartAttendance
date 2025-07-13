<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title')
                <div class="col-12 col-sm text-right text-sm-right mt-2 mt-sm-0">
                    <div class="btn-group mr-1 mt-2">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus fa-fw"></i> Tambah Jadwal Baru <i class="mdi mdi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" style="">
                            <a class="dropdown-item" href="{{ route('jadwal-kerja.create', 'tetap') }}">Jadwal Tetap</a>
                            <a class="dropdown-item" href="{{ route('jadwal-kerja.create', 'rotasi') }}">Jadwal Rotasi</a>
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
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th rowspan="2" class="text-center" width="10">
                                    <button class="btn btn-danger btn-sm delete-mulitple" id="btnDeleteSelected" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </th>
                                <th rowspan="2" class="text-center" style="max-width: 5px"></th>
                                <th rowspan="2" class="text-center" width="10">No</th>
                                <th rowspan="2" class="text-center">Kode Jadwal</th>
                                <th rowspan="2" class="text-center" style="min-width: 200px">Nama Jadwal</th>
                                <th rowspan="2" class="text-center" style="max-width: 70px">Tipe Jadwal</th>
                                <th colspan="5" class="text-center">Ketentuan Waktu Absen</th>
                                <th rowspan="2" class="text-center" style="min-width: 400px">Ketentuan Hari Kerja</th>

                            </tr>
                            <tr>
                                <th class="text-center">Mulai <br> Checkin</th>
                                <th class="text-center">Checkin <br> Ontime</th>
                                <th class="text-center">Batas <br> Checkin</th>
                                <th class="text-center">Waktu <br> Checkout</th>
                                <th class="text-center">Batas <br> Checkout</th>
                            </tr>
                            </thead>

                            <thead id="header-filter">
                                <tr>
                                    <th class="text-center">
                                        <input type="checkbox" class="check-data-all">
                                    </th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"><input type="text" class="form-control form-control-sm text-center search-col-dt"></th>
                                    <th class="text-center"><input type="text" class="form-control form-control-sm text-center search-col-dt"></th>
                                    <th class="text-center">
                                        <select name="" class="form-control form-control-sm search-col-dt text-center">
                                            <option value="">Semua</option>
                                            <option value="Tetap">Tetap</option>
                                            <option value="Rotasi">Rotasi</option>
                                        </select>
                                    </th>
                                    <th class="text-center" style=""></th>
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
    @include('schedule.atc.schedule_data_atc')
    @include('components.modal.modal_confirm_delete')
    @include('components.modal.modal_confirm_delete_multiple')
</div>
