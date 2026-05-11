<style>
    .rdp-item-row {
        display: grid;
        grid-template-columns: minmax(170px, 1.1fr) minmax(240px, 1.8fr) minmax(90px, .55fr) minmax(150px, .8fr) 42px;
        gap: 12px;
        align-items: start;
    }

    .rdp-item-textarea {
        min-height: 38px;
        overflow-wrap: anywhere;
        white-space: pre-wrap;
        resize: vertical;
    }

    @media (max-width: 1199.98px) {
        .rdp-item-row {
            grid-template-columns: minmax(170px, 1fr) minmax(240px, 1.6fr) minmax(90px, .55fr) minmax(140px, .75fr) 42px;
        }
    }

    @media (max-width: 991.98px) {
        .rdp-item-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="row">
    @foreach ($items as $index => $procurementItem)
        <div class="col-12" wire:key="pengadaan-item-{{ $index }}">
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Item Pengadaan #{{ $index + 1 }}</h6>
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
                        <label>Deskripsi Item <span class="text-danger">*</span></label>
                        <textarea wire:model="items.{{ $index }}.deskripsi_item" rows="1" class="form-control rdp-item-textarea @error('items.' . $index . '.deskripsi_item') is-invalid @enderror"></textarea>
                        @error('items.' . $index . '.deskripsi_item')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-0">
                        <label>Jumlah <span class="text-danger">*</span></label>
                        <input type="number" min="1" wire:model="items.{{ $index }}.jumlah" class="form-control @error('items.' . $index . '.jumlah') is-invalid @enderror">
                        @error('items.' . $index . '.jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-0">
                        <label>Satuan <span class="text-danger">*</span></label>
                        <select wire:model="items.{{ $index }}.satuan" class="form-control @error('items.' . $index . '.satuan') is-invalid @enderror">
                            <option value="">Pilih Satuan</option>
                            @foreach ($satuanList as $satuan)
                                <option value="{{ $satuan }}">{{ $satuan }}</option>
                            @endforeach
                        </select>
                        @error('items.' . $index . '.satuan')
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
