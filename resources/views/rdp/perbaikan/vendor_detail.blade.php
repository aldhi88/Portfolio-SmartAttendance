<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.vendor.perbaikan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left fa-fw"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @include('rdp.perbaikan.partials.detail', ['showCatatanRevisi' => false])
        </div>
    </div>

    @if ($item->status === \App\Repositories\RdpPerbaikanRepo::VENDOR_ASSIGNED_STATUS)
        <form wire:submit.prevent="wireSubmitProposal">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Upload Proposal</h5></div>
                <div class="card-body">
                    <div class="form-group mb-0">
                        <label>File Proposal PDF <span class="text-danger">*</span></label>
                        <input type="file" wire:model="fileProposal" accept="application/pdf" class="form-control @error('fileProposal') is-invalid @enderror">
                        @error('fileProposal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane fa-fw"></i> Kirim Proposal
                    </button>
                </div>
            </div>
        </form>
    @endif

    @if ($item->status === \App\Repositories\RdpPerbaikanRepo::WORK_RUNNING_STATUS)
        <form wire:submit.prevent="wireSubmitLaporan">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Laporan Hasil Perbaikan</h5></div>
                <div class="card-body">
                    @foreach ($item->rdp_perbaikan_items as $repairItem)
                        <div class="border rounded p-3 mb-3">
                            <h6>{{ $loop->iteration }}. {{ $repairItem->nama_item }}</h6>
                            <div class="form-group">
                                <label>Narasi Hasil Perbaikan <span class="text-danger">*</span></label>
                                <textarea wire:model="laporan.{{ $repairItem->id }}.narasi_hasil_perbaikan" rows="3" class="form-control @error('laporan.' . $repairItem->id . '.narasi_hasil_perbaikan') is-invalid @enderror"></textarea>
                                @error('laporan.' . $repairItem->id . '.narasi_hasil_perbaikan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label>Foto Hasil Perbaikan <span class="text-danger">*</span></label>
                                <input type="file" wire:model="laporan.{{ $repairItem->id }}.foto_hasil_perbaikan" accept="image/*" class="form-control @error('laporan.' . $repairItem->id . '.foto_hasil_perbaikan') is-invalid @enderror">
                                @error('laporan.' . $repairItem->id . '.foto_hasil_perbaikan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane fa-fw"></i> Kirim Laporan
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
