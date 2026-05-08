<div wire:ignore.self class="modal fade" id="modalReviewBerkas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="msg mb-3">Minta revisi berkas</h5>
                        <div class="form-group">
                            <label>Catatan Revisi Berkas <span class="text-danger">*</span></label>
                            <textarea wire:model="catatanRevisiBerkas" rows="4" class="form-control @error('catatanRevisiBerkas') is-invalid @enderror"></textarea>
                            @error('catatanRevisiBerkas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="text-right mt-4">
                    <button type="button" class="btn btn-light waves-effect px-4" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning waves-effect waves-light px-4" wire:click="wireRequestRevisionBerkas">
                        Kirim Revisi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
