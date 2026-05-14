<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.pengajuan.permintaan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus fa-fw"></i> Ajukan Permintaan
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('rdp.permintaan.partials.data_table')
    @include('rdp.permintaan.atc.data_atc', [
        'role' => 'karyawan',
        'ajaxRoute' => route('rdp.pengajuan.permintaan.indexDT'),
        'detailBase' => url('rdp/pengajuan/permintaan/detail'),
    ])
</div>
