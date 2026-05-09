<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
            </div>
        </div>
    </div>
    @include('rdp.perbaikan.partials.data_table')
    @include('rdp.perbaikan.atc.data_atc', [
        'role' => 'pimpinan',
        'ajaxRoute' => route('rdp.persetujuan.perbaikan.indexDT'),
        'detailBase' => url('rdp/persetujuan/perbaikan/detail'),
        'editBase' => '',
        'spkBase' => url('rdp/perbaikan/spk'),
    ])
    @include('components.modal.modal_confirm_delete')
</div>
