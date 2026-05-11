<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    @if (in_array($item->status, \App\Repositories\RdpPengadaanRepo::EDITABLE_STATUS, true))
                        <a href="{{ route('rdp.pengajuan.pengadaan.edit', $item->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit fa-fw"></i> Edit Data
                        </a>
                    @endif
                    <a href="{{ route('rdp.pengajuan.pengadaan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left fa-fw"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @include('rdp.pengadaan.partials.detail')
        </div>
    </div>
</div>
