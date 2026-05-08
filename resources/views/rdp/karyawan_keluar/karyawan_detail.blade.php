<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    @if (in_array($item->status, \App\Repositories\RdpKaryawanKeluarRepo::EDITABLE_STATUS))
                        <a href="{{ route('rdp.pengajuan.izin-keluar.edit', $item->id) }}" class="btn btn-primary">
                            Edit Data
                        </a>
                    @endif
                    @if ($item->status === \App\Repositories\RdpKaryawanKeluarRepo::PIMPINAN_APPROVED_STATUS)
                        <a href="{{ route('rdp.pengajuan.izin-keluar.pendataan-aset', $item->id) }}" class="btn btn-primary">
                            Pendataan Aset
                        </a>
                    @endif
                    <a href="{{ route('rdp.pengajuan.izin-keluar.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('rdp.karyawan_keluar.partials.detail')
</div>
