<div>
    <div class="loading-50" wire:loading wire:target="wireSubmit">
        <div class="loader"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.penempatan.izin-penempatan.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <form wire:submit.prevent="wireSubmit">
        <div class="card">
            <div class="card-body">
                @if ($isReviewStep)
                    @include('rdp.karyawan_masuk.partials.admin_form', ['showAdminReviewFields' => false])
                @else
                    @include('rdp.karyawan_masuk.partials.admin_form', ['showAdminReviewFields' => true])
                @endif
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        {{ $isReviewStep ? 'Simpan Perubahan & Setujui' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
