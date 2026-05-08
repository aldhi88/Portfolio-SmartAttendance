<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Nomor SK Keluar <span class="text-danger">*</span></label>
            <input type="text" wire:model="form.nomor_sk_keluar" class="form-control @error('form.nomor_sk_keluar') is-invalid @enderror">
            @error('form.nomor_sk_keluar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal SK Keluar <span class="text-danger">*</span></label>
            <input type="date" wire:model="form.tanggal_sk_keluar" class="form-control @error('form.tanggal_sk_keluar') is-invalid @enderror">
            @error('form.tanggal_sk_keluar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal Keluar <span class="text-danger">*</span></label>
            <input type="date" wire:model="form.tanggal_keluar" class="form-control @error('form.tanggal_keluar') is-invalid @enderror">
            @error('form.tanggal_keluar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label>File SK Keluar / Dokumen Pendukung <span class="text-danger">*</span></label>
    <input type="file" wire:model="form.file_sk_keluar" class="form-control @error('form.file_sk_keluar') is-invalid @enderror">
    @isset($fileOld)
        @if ($fileOld)
            <small class="form-text text-muted">
                File saat ini:
                <a href="{{ asset('storage/' . \App\Repositories\RdpKaryawanKeluarRepo::FILE_DIR . '/' . $fileOld) }}" target="_blank">Lihat file</a>
            </small>
        @endif
    @endisset
    @error('form.file_sk_keluar')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
