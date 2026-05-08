<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Nomor SK Mutasi <span class="text-danger">*</span></label>
            <input type="text" wire:model="form.nomor_sk_mutasi" class="form-control @error('form.nomor_sk_mutasi') is-invalid @enderror">
            @error('form.nomor_sk_mutasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal SK Mutasi <span class="text-danger">*</span></label>
            <input type="date" wire:model="form.tanggal_sk_mutasi" class="form-control @error('form.tanggal_sk_mutasi') is-invalid @enderror">
            @error('form.tanggal_sk_mutasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal Mulai <span class="text-danger">*</span></label>
            <input type="date" wire:model="form.tanggal_mulai" class="form-control @error('form.tanggal_mulai') is-invalid @enderror">
            @error('form.tanggal_mulai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label>File SK Mutasi / Dokumen Pendukung <span class="text-danger">*</span></label>
    <input type="file" wire:model="form.file_sk_mutasi" class="form-control @error('form.file_sk_mutasi') is-invalid @enderror">
    @isset($fileOld)
        @if ($fileOld)
            <small class="form-text text-muted">
                File saat ini:
                <a href="{{ asset('storage/' . \App\Repositories\RdpKaryawanMasukRepo::FILE_DIR . '/' . $fileOld) }}" target="_blank">Lihat file</a>
            </small>
        @endif
    @endisset
    @error('form.file_sk_mutasi')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
