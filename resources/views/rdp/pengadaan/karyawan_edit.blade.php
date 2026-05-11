<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.pengajuan.pengadaan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left fa-fw"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    @if (!$isEditable)
        <div class="alert alert-warning">Pengajuan tidak bisa diedit pada status saat ini.</div>
    @endif
    @if ($item->catatan_revisi)
        <div class="alert alert-info"><strong>Catatan Revisi:</strong> {{ $item->catatan_revisi }}</div>
    @endif
    <form wire:submit.prevent="wireSubmit">
        <div class="card">
            <div class="card-body">
                @include('rdp.pengadaan.partials.form', ['mode' => 'karyawan', 'penempatan' => $item->rdp_karyawan_masuks, 'showVendor' => false, 'showStatus' => false])
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" @if (!$isEditable) disabled @endif>
                    <i class="fas fa-paper-plane fa-fw"></i> Simpan & Kirim Ulang
                </button>
            </div>
        </div>
    </form>
</div>
