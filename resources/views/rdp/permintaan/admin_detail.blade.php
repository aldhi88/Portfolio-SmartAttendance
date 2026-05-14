<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    @if ($item->status === \App\Repositories\RdpPermintaanRepo::DEFAULT_STATUS)
                        <button type="button" class="btn btn-success" wire:click="wireComplete">
                            <i class="fas fa-check fa-fw"></i> Tandai Selesai
                        </button>
                    @endif
                    <a href="{{ route('rdp.permintaan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left fa-fw"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @include('rdp.permintaan.partials.detail')
        </div>
    </div>
</div>
