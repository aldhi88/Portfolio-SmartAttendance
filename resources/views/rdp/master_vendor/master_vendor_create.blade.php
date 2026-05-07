<div>
    <div wire:ignore.self class="modal fade" id="modalCreate" tabindex="-1">
        <div class="modal-dialog modal-lg">
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
                                    <label>Nama Vendor</label>
                                    <input type="text" wire:model="store.nama" class="modalOnFocus form-control @error('store.nama') is-invalid @enderror">
                                    @error('store.nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="text" wire:model="store.telp" class="form-control @error('store.telp') is-invalid @enderror">
                                    @error('store.telp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea wire:model="store.alamat" rows="3" class="form-control @error('store.alamat') is-invalid @enderror"></textarea>
                                    @error('store.alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select wire:model="store.status" class="form-control @error('store.status') is-invalid @enderror">
                                        @foreach ($statusOptions as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @error('store.status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <h5>Data Login</h5>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" wire:model="store.username" class="form-control @error('store.username') is-invalid @enderror">
                                    @error('store.username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="text" wire:model="store.password" class="form-control @error('store.password') is-invalid @enderror">
                                    @error('store.password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Tambahkan Data</button>
                    </div>
                </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
