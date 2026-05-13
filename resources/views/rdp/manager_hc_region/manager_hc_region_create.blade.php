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
                        <div class="form-group">
                            <label>Nama Manager</label>
                            <input type="text" wire:model="store.nickname" class="modalOnFocus form-control @error('store.nickname') is-invalid @enderror">
                            @error('store.nickname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <h5>Data Login</h5>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" wire:model="store.username" class="form-control @error('store.username') is-invalid @enderror">
                            @error('store.username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" wire:model="store.password" class="form-control @error('store.password') is-invalid @enderror">
                            @error('store.password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Tambahkan Data</button>
                    </div>
                </form>
        </div>
    </div>
</div>
