<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.pengajuan.perbaikan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus fa-fw"></i> Ajukan Perbaikan
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('rdp.perbaikan.partials.data_table')
    @include('rdp.perbaikan.atc.data_atc', [
        'role' => 'karyawan',
        'ajaxRoute' => route('rdp.pengajuan.perbaikan.indexDT'),
        'detailBase' => url('rdp/pengajuan/perbaikan/detail'),
        'editBase' => url('rdp/pengajuan/perbaikan/edit'),
        'spkBase' => url('rdp/perbaikan/spk'),
    ])
    @include('components.modal.modal_confirm_delete')
</div>
