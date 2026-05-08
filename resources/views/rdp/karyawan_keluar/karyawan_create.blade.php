<div>
    <div class="loading-50" wire:loading wire:target="wireSubmit">
        <div class="loader"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.pengajuan.izin-keluar.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <form wire:submit.prevent="wireSubmit">
        <div class="card">
            <div class="card-body">
                @if (!$rumah)
                    <div class="alert alert-warning">
                        Anda belum memiliki data rumah aktif yang bisa diajukan izin keluar.
                    </div>
                @else
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div><strong>Rumah:</strong> {{ collect([$rumah->block, $rumah->tipe, $rumah->nomor])->filter()->implode(' ') }}</div>
                        <div><strong>Cluster:</strong> {{ $rumah->rdp_master_clusters?->nama_cluster ?: '-' }}</div>
                        <div><strong>Status Rumah:</strong> {{ $rumah->status ?: '-' }}</div>
                    </div>
                @endif
                @include('rdp.karyawan_keluar.partials.karyawan_form')
                <div class="text-right">
                    <button type="submit" class="btn btn-primary" @disabled(!$rumah)>Kirim Pengajuan</button>
                </div>
            </div>
        </div>
    </form>
</div>
