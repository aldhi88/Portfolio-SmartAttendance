<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
            </div>
        </div>
    </div>
    @include('rdp.pengadaan.partials.data_table')
    @include('rdp.pengadaan.atc.data_atc', [
        'role' => 'vendor',
        'ajaxRoute' => route('rdp.vendor.pengadaan.indexDT'),
        'detailBase' => url('rdp/vendor/pengadaan/detail'),
        'editBase' => '',
        'spkBase' => url('rdp/vendor/pengadaan/spk'),
    ])
</div>
