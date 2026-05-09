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
        'role' => 'vendor',
        'ajaxRoute' => route('rdp.vendor.perbaikan.indexDT'),
        'detailBase' => url('rdp/vendor/perbaikan/detail'),
        'editBase' => '',
        'spkBase' => url('rdp/perbaikan/spk'),
    ])
</div>
