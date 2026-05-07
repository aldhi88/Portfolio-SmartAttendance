<div>
    <div wire:ignore.self class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Form Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="wireUpdate" class="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Nama Vendor</label>
                                    <input type="text" wire:model="update.nama" class="modalOnFocus form-control @error('update.nama') is-invalid @enderror">
                                    @error('update.nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="text" wire:model="update.telp" class="form-control @error('update.telp') is-invalid @enderror">
                                    @error('update.telp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea wire:model="update.alamat" rows="3" class="form-control @error('update.alamat') is-invalid @enderror"></textarea>
                                    @error('update.alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select wire:model="update.status" class="form-control @error('update.status') is-invalid @enderror">
                                        @foreach ($statusOptions as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @error('update.status')
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
                                    <input type="text" wire:model="update.username" class="form-control @error('update.username') is-invalid @enderror">
                                    @error('update.username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Ubah Password</label>
                                    <input type="text" wire:model="update.password" class="form-control @error('update.password') is-invalid @enderror">
                                    @error('update.password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan Perubahan</button>
                    </div>
                </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    @push('push-script')
        <script>
            $('#modalEdit').on('show.bs.modal', function(e) {
                const id = $(e.relatedTarget).data('id');
                Livewire.dispatch('setEditData', {id: id});
            });
        </script>
    @endpush
</div>
