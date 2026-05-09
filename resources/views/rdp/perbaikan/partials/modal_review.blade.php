<div wire:ignore.self class="modal fade" id="modalReviewPerbaikan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="msg mb-3">Minta revisi pengajuan perbaikan</h5>
                <div class="form-group">
                    <label>Catatan Revisi <span class="text-danger">*</span></label>
                    <textarea wire:model="catatanRevisi" rows="4" class="form-control @error('catatanRevisi') is-invalid @enderror"></textarea>
                    @error('catatanRevisi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-light px-4" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning px-4" wire:click="wireRequestRevision">Kirim Revisi</button>
                </div>
            </div>
        </div>
    </div>
</div>
