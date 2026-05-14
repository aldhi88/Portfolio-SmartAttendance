<div>
    <div wire:ignore.self class="modal fade" id="modalEditSignature" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Edit Tanda Tangan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form wire:submit.prevent="wireSubmit" enctype="multipart/form-data">
                    <div class="modal-body">
                        @if ($currentTtdUrl)
                            <div class="form-group">
                                <label>Tanda Tangan Saat Ini</label>
                                <div>
                                    <img src="{{ $currentTtdUrl }}" alt="Tanda tangan saat ini" class="img-fluid border rounded p-1" style="max-height: 90px;">
                                </div>
                            </div>
                        @endif

                        <div class="form-group mb-0">
                            <label>File Gambar Tanda Tangan</label>
                            <input type="file" wire:model="ttd" accept="image/*" class="form-control @error('ttd') is-invalid @enderror">
                            @error('ttd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Format JPG/PNG, maksimal 2 MB.</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light" wire:loading.attr="disabled" wire:target="ttd,wireSubmit">
                            <i class="fas fa-save fa-fw"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
