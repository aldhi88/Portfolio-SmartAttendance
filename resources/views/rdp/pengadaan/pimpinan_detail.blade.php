<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    @if (in_array($item->status, \App\Repositories\RdpPengadaanRepo::PIMPINAN_ACTIONABLE_STATUS, true))
                        <button type="button" class="btn btn-success" wire:click="wireApprove">
                            <i class="fas fa-check fa-fw"></i> Setujui
                        </button>
                    @endif
                    @if ($item->status === \App\Repositories\RdpPengadaanRepo::PROPOSAL_SPV_APPROVED_STATUS)
                        <button type="button" class="btn btn-danger" wire:click="wireReject">
                            <i class="fas fa-times fa-fw"></i> Tolak
                        </button>
                    @endif
                    <a href="{{ route('rdp.persetujuan.pengadaan.index') }}" class="btn btn-secondary">
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
