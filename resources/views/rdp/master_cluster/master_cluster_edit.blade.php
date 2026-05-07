<div>
    <div class="loading-50" wire:loading wire:target="wireSubmit">
        <div class="loader"></div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between row">
                @include('components.app_layout_title', ['pass' => $data])
                <div class="col-12 col-sm text-left text-sm-right mt-2 mt-sm-0">
                    <a href="{{ route('rdp.master.cluster.index') }}" class="btn btn-secondary">
                        Kembali <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="wireSubmit">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Cluster</label>
                            <input type="text" wire:model="form.nama_cluster" class="form-control @error('form.nama_cluster') is-invalid @enderror">
                            @error('form.nama_cluster')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h4 class="lead mt-4">Standar Aset Cluster</h4>
                        @include('rdp.master_cluster.partials.form_detail_aset')

                        <div class="mt-3 text-right">
                            <button type="button" wire:click="addRow" class="btn btn-success waves-effect">
                                <i class="fas fa-plus fa-fw"></i> Tambah Baris
                            </button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
