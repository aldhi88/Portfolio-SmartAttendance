<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.vendor.pengadaan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left fa-fw"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @include('rdp.pengadaan.partials.detail', ['showCatatanRevisi' => false])
        </div>
    </div>

    @if ($item->status === \App\Repositories\RdpPengadaanRepo::VENDOR_ASSIGNED_STATUS)
        <form wire:submit.prevent="wireSubmitProposal">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Item dan Proposal Pengadaan</h5></div>
                <div class="card-body">
                    @include('rdp.pengadaan.partials.item_form')
                    <hr>
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

    @if ($item->status === \App\Repositories\RdpPengadaanRepo::WORK_RUNNING_STATUS)
        <form wire:submit.prevent="wireSubmitLaporan">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Laporan Hasil Pengadaan</h5></div>
                <div class="card-body">
                    @foreach ($item->rdp_pengadaan_items as $procurementItem)
                        <div class="border rounded p-3 mb-3">
                            <h6>{{ $loop->iteration }}. {{ $procurementItem->nama_item }}</h6>
                            <div class="form-group">
                                <label>Narasi Hasil Pengadaan <span class="text-danger">*</span></label>
                                <textarea wire:model="laporan.{{ $procurementItem->id }}.narasi_hasil_pengadaan" rows="3" class="form-control @error('laporan.' . $procurementItem->id . '.narasi_hasil_pengadaan') is-invalid @enderror"></textarea>
                                @error('laporan.' . $procurementItem->id . '.narasi_hasil_pengadaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label>Foto Hasil Pengadaan <span class="text-danger">*</span></label>
                                <input type="file" wire:model="laporan.{{ $procurementItem->id }}.foto_hasil_pengadaan" accept="image/*" class="form-control @error('laporan.' . $procurementItem->id . '.foto_hasil_pengadaan') is-invalid @enderror">
                                @error('laporan.' . $procurementItem->id . '.foto_hasil_pengadaan')
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
