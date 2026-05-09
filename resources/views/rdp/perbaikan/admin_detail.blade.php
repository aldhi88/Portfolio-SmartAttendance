<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.perbaikan.edit', $item->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit fa-fw"></i> {{ in_array($item->status, \App\Repositories\RdpPerbaikanRepo::ADMIN_REVIEWABLE_STATUS, true) ? 'Edit & Setujui' : 'Edit Data' }}
                    </a>
                    <a href="{{ route('rdp.perbaikan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left fa-fw"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                @if (in_array($item->status, \App\Repositories\RdpPerbaikanRepo::ADMIN_REVIEWABLE_STATUS, true))
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalReviewPerbaikan">
                        <i class="fas fa-undo fa-fw"></i> Minta Revisi
                    </button>
                @endif
                @if ($item->status === \App\Repositories\RdpPerbaikanRepo::PROPOSAL_SUBMITTED_STATUS)
                    <button type="button" class="btn btn-success" wire:click="wireApproveProposal">
                        <i class="fas fa-check fa-fw"></i> Setujui Proposal
                    </button>
                    <button type="button" class="btn btn-warning" wire:click="wireRequestProposalRevision">
                        <i class="fas fa-undo fa-fw"></i> Kembalikan Proposal
                    </button>
                @endif
                @if ($item->status === \App\Repositories\RdpPerbaikanRepo::SPK_READY_STATUS)
                    <button type="button" class="btn btn-primary" wire:click="wirePublishSpk">
                        <i class="fas fa-file-signature fa-fw"></i> Terbitkan SPK
                    </button>
                @endif
                @if ($item->status === \App\Repositories\RdpPerbaikanRepo::VENDOR_FINISHED_STATUS)
                    <button type="button" class="btn btn-success" wire:click="wireApproveLaporan">
                        <i class="fas fa-check fa-fw"></i> Setujui Laporan Vendor
                    </button>
                @endif
                @if (!in_array($item->status, [\App\Repositories\RdpPerbaikanRepo::FINISHED_STATUS, \App\Repositories\RdpPerbaikanRepo::CANCEL_STATUS], true))
                    <button type="button" class="btn btn-danger" wire:click="wireCancel">
                        <i class="fas fa-ban fa-fw"></i> Batalkan
                    </button>
                @endif
            </div>

            @include('rdp.perbaikan.partials.detail')
        </div>
    </div>

    @include('rdp.perbaikan.partials.modal_review')
</div>
