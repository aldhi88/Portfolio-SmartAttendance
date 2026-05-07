<div>
    {{-- <div class="loading-50" wire:loading><div class="loader"></div></div> --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.master.aset.create') }}" class="btn btn-info">
                        <i class="fas fa-list fa-fw"></i> Tambah Banyak Data
                    </a>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                        <i class="fas fa-plus fa-fw"></i> Tambah Data Baru
                    </button>
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
                                <th class="text-center" width="10">
                                    <button class="btn btn-danger btn-sm delete-mulitple" id="btnDeleteSelected" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </th>
                                <th class="text-center" style="max-width: 5px"></th>
                                <th class="text-center" width="10">No</th>
                                <th class="text-center">Perlengkapan</th>
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
                                        <input type="text" class="form-control form-control-sm text-center search-col-dt">
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
    @include('rdp.master_aset.atc.master_aset_data_atc')
    @include('components.modal.modal_confirm_delete')
    @include('components.modal.modal_confirm_delete_multiple')
    @include('rdp.master_aset.master_aset_create_modal')
    @include('rdp.master_aset.master_aset_edit')
</div>
