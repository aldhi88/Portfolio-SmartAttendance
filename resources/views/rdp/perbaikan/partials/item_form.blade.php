<style>
    .rdp-item-row {
        display: grid;
        grid-template-columns: minmax(180px, 1fr) minmax(260px, 2fr) minmax(210px, 1.2fr) 42px;
        gap: 12px;
        align-items: start;
    }

    .rdp-item-textarea {
        min-height: 38px;
        overflow-wrap: anywhere;
        white-space: pre-wrap;
        resize: vertical;
    }

    @media (max-width: 991.98px) {
        .rdp-item-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="row">
    @foreach ($items as $index => $repairItem)
        <div class="col-12" wire:key="perbaikan-item-{{ $index }}">
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Item Perbaikan #{{ $index + 1 }}</h6>
                </div>
                <div class="rdp-item-row">
                    <div class="form-group mb-0">
                        <label>Nama Item <span class="text-danger">*</span></label>
                        <textarea wire:model="items.{{ $index }}.nama_item" rows="1" class="form-control rdp-item-textarea @error('items.' . $index . '.nama_item') is-invalid @enderror"></textarea>
                        @error('items.' . $index . '.nama_item')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-0">
                        <label>Deskripsi Detail Kerusakan <span class="text-danger">*</span></label>
                        <textarea wire:model="items.{{ $index }}.deskripsi_kerusakan" rows="1" class="form-control rdp-item-textarea @error('items.' . $index . '.deskripsi_kerusakan') is-invalid @enderror"></textarea>
                        @error('items.' . $index . '.deskripsi_kerusakan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-0">
                        <label>Foto Kerusakan <span class="text-danger">*</span></label>
                        <input type="file" wire:model="items.{{ $index }}.foto_kerusakan" accept="image/*" class="form-control @error('items.' . $index . '.foto_kerusakan') is-invalid @enderror">
                        @if (!empty($repairItem['foto_kerusakan_old']))
                            <small class="d-block mt-1">
                                <a href="{{ asset('storage/' . \App\Repositories\RdpPerbaikanRepo::FILE_DIR_KERUSAKAN . '/' . $repairItem['foto_kerusakan_old']) }}" target="_blank">Lihat foto lama</a>
                            </small>
                        @endif
                        @error('items.' . $index . '.foto_kerusakan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-0 pt-md-4">
                        @if (count($items) > 1)
                            <button type="button" class="btn btn-danger btn-block" wire:click="removeItem({{ $index }})">
                                <i class="fas fa-trash-alt fa-fw"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-12">
        <button type="button" class="btn btn-outline-primary" wire:click="addItem">
            <i class="fas fa-plus fa-fw"></i> Tambah Item
        </button>
    </div>
</div>
