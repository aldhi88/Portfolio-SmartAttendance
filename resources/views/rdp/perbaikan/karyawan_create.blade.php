<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.pengajuan.perbaikan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left fa-fw"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    <form wire:submit.prevent="wireSubmit">
        <div class="card">
            <div class="card-body">
                @include('rdp.perbaikan.partials.form', ['mode' => 'karyawan', 'showVendor' => false, 'showStatus' => false])
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" @if (!$penempatan) disabled @endif>
                    <i class="fas fa-paper-plane fa-fw"></i> Ajukan Perbaikan
                </button>
            </div>
        </div>
    </form>
</div>
