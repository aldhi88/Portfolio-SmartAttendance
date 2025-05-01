<div>
    <div wire:ignore.self class="modal fade" id="modalCreate" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Form Data Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="wireStore" class="restart">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" wire:model="store.name" class="modalOnFocus form-control @error('store.name') is-invalid @enderror">
                                    @error('store.name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Tambahkan Data</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
