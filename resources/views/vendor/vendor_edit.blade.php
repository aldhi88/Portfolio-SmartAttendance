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
                                    <label>Nama Pengguna</label>
                                    <input type="text" wire:model="update.name" class="modalOnFocus form-control @error('update.name') is-invalid @enderror">
                                    @error('update.name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Perusahaan</label>
                                    <select wire:model="update.master_organization_id" class="form-control">
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
                                    <input type="text" wire:model="update.username" class=" form-control @error('update.username') is-invalid @enderror">
                                    @error('update.username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Ubah Password</label>
                                    <input type="text" wire:model="update.password" class=" form-control @error('update.password') is-invalid @enderror">
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
