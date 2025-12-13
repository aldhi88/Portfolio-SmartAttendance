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
                                    <label>Nama Pengguna</label>
                                    <input type="text" wire:model="store.name" class="modalOnFocus form-control @error('store.name') is-invalid @enderror">
                                    @error('store.name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Perusahaan</label>
                                    <select wire:model="store.master_organization_id" class="form-control">
                                        @foreach ($dt['org'] as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <h5>Data Login</h5>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" wire:model="store.username" class=" form-control @error('store.username') is-invalid @enderror">
                                    @error('store.username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="text" wire:model="store.password" class=" form-control @error('store.password') is-invalid @enderror">
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
