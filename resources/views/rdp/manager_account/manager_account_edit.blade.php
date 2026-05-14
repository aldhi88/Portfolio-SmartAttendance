<div>
    <div wire:ignore.self class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Form Edit Akun Manager</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="wireUpdate" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Jabatan Sebagai</label>
                            <input type="text" wire:model="update.role_name" class="form-control @error('update.role_name') is-invalid @enderror">
                            @error('update.role_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Dipakai sebagai jabatan pada cetakan SIP/SPK. Hak akses login tetap mengikuti role sistem.</small>
                        </div>
                        <div class="form-group">
                            <label>Nama Manager</label>
                            <input type="text" wire:model="update.nickname" class="modalOnFocus form-control @error('update.nickname') is-invalid @enderror">
                            @error('update.nickname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <h5>Data Login</h5>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" wire:model="update.username" class="form-control @error('update.username') is-invalid @enderror">
                            @error('update.username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Ubah Password</label>
                            <input type="text" wire:model="update.password" class="form-control @error('update.password') is-invalid @enderror">
                            @error('update.password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>File Gambar Tanda Tangan</label>
                            <input type="file" wire:model="ttd" accept="image/*" class="form-control @error('ttd') is-invalid @enderror">
                            @error('ttd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if (!empty($update['ttd_old']))
                                <div class="mt-2">
                                    <div class="small text-muted mb-1">Tanda tangan saat ini</div>
                                    <img src="{{ asset('storage/' . \App\Repositories\RdpManagerAccountRepo::FILE_DIR_TTD . '/' . $update['ttd_old']) }}" alt="Tanda tangan manager" class="img-fluid border rounded p-1" style="max-height:80px;">
                                </div>
                            @endif
                            <small class="form-text text-muted">Format JPG/PNG, maksimal 2 MB. Kosongkan jika tidak ingin mengganti file.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan Perubahan</button>
                    </div>
                </form>
        </div>
    </div>
    @push('push-script')
        <script>
            $('#modalEdit').on('show.bs.modal', function(e) {
                const id = $(e.relatedTarget).data('id');
                Livewire.dispatch('setEditData', {id: id});
            });
        </script>
    @endpush
</div>
