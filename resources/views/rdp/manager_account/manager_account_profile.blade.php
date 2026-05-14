<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="wireSubmit" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Jabatan Sebagai</label>
                    <input type="text" wire:model="form.role_name" class="form-control @error('form.role_name') is-invalid @enderror">
                    @error('form.role_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Dipakai sebagai jabatan pada cetakan SIP/SPK. Hak akses login tetap mengikuti role sistem.</small>
                </div>
                <div class="form-group">
                    <label>Nama Manager</label>
                    <input type="text" wire:model="form.nickname" class="form-control @error('form.nickname') is-invalid @enderror">
                    @error('form.nickname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <h5>Data Login</h5>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" wire:model="form.username" class="form-control @error('form.username') is-invalid @enderror">
                    @error('form.username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Ubah Password</label>
                    <input type="text" wire:model="form.password" class="form-control @error('form.password') is-invalid @enderror">
                    @error('form.password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>File Gambar Tanda Tangan</label>
                    <input type="file" wire:model="ttd" accept="image/*" class="form-control @error('ttd') is-invalid @enderror">
                    @error('ttd')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if (!empty($form['ttd_old']))
                        <div class="mt-2">
                            <div class="small text-muted mb-1">Tanda tangan saat ini</div>
                            <img src="{{ asset('storage/' . \App\Repositories\RdpManagerAccountRepo::FILE_DIR_TTD . '/' . $form['ttd_old']) }}" alt="Tanda tangan manager" class="img-fluid border rounded p-1" style="max-height:80px;">
                        </div>
                    @endif
                    <small class="form-text text-muted">Format JPG/PNG, maksimal 2 MB. Kosongkan jika tidak ingin mengganti file.</small>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save fa-fw"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
