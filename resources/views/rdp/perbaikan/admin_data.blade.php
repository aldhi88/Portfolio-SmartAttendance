<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.perbaikan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus fa-fw"></i> Tambah Data Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('rdp.perbaikan.partials.data_table')
    @include('rdp.perbaikan.atc.data_atc', [
        'role' => 'admin',
        'ajaxRoute' => route('rdp.perbaikan.indexDT'),
        'detailBase' => url('rdp/perbaikan/detail'),
        'editBase' => url('rdp/perbaikan/edit'),
        'spkBase' => url('rdp/perbaikan/spk'),
    ])
    @include('components.modal.modal_confirm_delete')
    @include('rdp.perbaikan.partials.modal_review')
</div>
