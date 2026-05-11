<div wire:ignore.self class="modal fade" id="modalReviewPengadaan" tabindex="-1">
    <div class="modal-dialog">
        <form wire:submit.prevent="wireRequestRevision" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Minta Revisi Pengadaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="msg mb-3"></p>
                <div class="form-group mb-0">
                    <label>Catatan Revisi <span class="text-danger">*</span></label>
                    <textarea wire:model="catatanRevisi" rows="4" class="form-control @error('catatanRevisi') is-invalid @enderror"></textarea>
                    @error('catatanRevisi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-undo fa-fw"></i> Kirim Revisi
                </button>
            </div>
        </form>
    </div>
</div>
