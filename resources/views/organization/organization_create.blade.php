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
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" wire:model="store.is_rdp_eligible" id="store_is_rdp_eligible" class="custom-control-input @error('store.is_rdp_eligible') is-invalid @enderror">
                                        <label class="custom-control-label" for="store_is_rdp_eligible">Berhak Fasilitas RDP</label>
                                        @error('store.is_rdp_eligible')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
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
