<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    @if ($item->status === \App\Repositories\RdpKaryawanMasukRepo::HC_REGION_PENDING_STATUS)
                        <button type="button" class="btn btn-success"
                            data-toggle="modal" data-target="#modalConfirmDelete"
                            data-json='@json(["msg" => "Apakah anda yakin menyetujui izin penempatan " . ($item->data_employees?->name ?: "-") . "?", "id" => $item->id])'
                            data-dispatch="wireApprove()"
                            data-submit-label="Setujui">
                            <i class="fas fa-check fa-fw"></i> Setujui
                        </button>
                        <button type="button" class="btn btn-danger"
                            data-toggle="modal" data-target="#modalConfirmDelete"
                            data-json='@json(["msg" => "Apakah anda yakin menolak izin penempatan " . ($item->data_employees?->name ?: "-") . "?", "id" => $item->id])'
                            data-dispatch="wireReject()"
                            data-submit-label="Tolak">
                            <i class="fas fa-ban fa-fw"></i> Tolak
                        </button>
                    @endif
                    <a href="{{ route('rdp.hc-region.izin-penempatan.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('rdp.karyawan_masuk.partials.detail')
    @include('components.modal.modal_confirm_delete')
</div>
