<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    @if (in_array($item->status, \App\Repositories\RdpKaryawanKeluarRepo::ADMIN_REVIEWABLE_STATUS))
                        <button type="button" class="btn btn-success"
                            data-toggle="modal" data-target="#modalApproveBerkas"
                            data-json='@json(["msg" => "Setujui berkas pengajuan " . ($item->data_employees?->name ?: "-"), "id" => $item->id])'>
                            <i class="fas fa-check fa-fw"></i> Setujui Berkas
                        </button>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalReviewBerkas">
                            <i class="fas fa-undo fa-fw"></i> Minta Revisi
                        </button>
                    @endif
                    @if ($item->status !== \App\Repositories\RdpKaryawanKeluarRepo::FINISHED_STATUS)
                        <button type="button" class="btn btn-danger"
                            data-toggle="modal" data-target="#modalConfirmDelete"
                            data-json='@json(["msg" => "Apakah anda yakin menolak/membatalkan pengajuan " . ($item->data_employees?->name ?: "-") . "?", "id" => $item->id])'
                            data-dispatch="wireCancelKeluarRdp()"
                            data-submit-label="Tolak">
                            <i class="fas fa-ban fa-fw"></i> Tolak/Batalkan
                        </button>
                    @endif
                    <a href="{{ route('rdp.keluar-rdp.izin-keluar.edit', $item->id) }}" class="btn btn-primary">
                        Edit Data
                    </a>
                    @if ($item->status === \App\Repositories\RdpKaryawanKeluarRepo::PIMPINAN_APPROVED_STATUS)
                        <a href="{{ route('rdp.keluar-rdp.izin-keluar.pendataan-aset', $item->id) }}" class="btn btn-primary">
                            Pendataan Aset
                        </a>
                    @endif
                    @if ($item->status === \App\Repositories\RdpKaryawanKeluarRepo::ASSET_SUBMITTED_STATUS)
                        <button type="button" class="btn btn-success"
                            data-toggle="modal" data-target="#modalConfirmDelete"
                            data-json='@json(["msg" => "Apakah anda yakin menyetujui pendataan aset " . ($item->data_employees?->name ?: "-") . "?", "id" => $item->id])'
                            data-dispatch="wireApprovePendataanAset()"
                            data-submit-label="Setujui">
                            <i class="fas fa-check fa-fw"></i> Setujui Pendataan Aset
                        </button>
                    @endif
                    <a href="{{ route('rdp.keluar-rdp.izin-keluar.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('rdp.karyawan_keluar.partials.detail')
    @include('components.modal.modal_confirm_delete')
    @include('rdp.karyawan_keluar.partials.modal_approve_berkas')
    @include('rdp.karyawan_keluar.partials.modal_review_berkas')
</div>
