<div class="row">
    @foreach ($items as $index => $procurementItem)
        <div class="col-12" wire:key="pengadaan-item-{{ $index }}">
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Item Pengadaan #{{ $index + 1 }}</h6>
                    @if (count($items) > 1)
                        <button type="button" class="btn btn-sm btn-danger" wire:click="removeItem({{ $index }})">
                            <i class="fas fa-trash-alt fa-fw"></i>
                        </button>
                    @endif
                </div>
                <div class="form-group">
                    <label>Nama Item <span class="text-danger">*</span></label>
                    <input type="text" wire:model="items.{{ $index }}.nama_item" class="form-control @error('items.' . $index . '.nama_item') is-invalid @enderror">
                    @error('items.' . $index . '.nama_item')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Deskripsi Item <span class="text-danger">*</span></label>
                    <textarea wire:model="items.{{ $index }}.deskripsi_item" rows="3" class="form-control @error('items.' . $index . '.deskripsi_item') is-invalid @enderror"></textarea>
                    @error('items.' . $index . '.deskripsi_item')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-md-0">
                            <label>Jumlah <span class="text-danger">*</span></label>
                            <input type="number" min="1" wire:model="items.{{ $index }}.jumlah" class="form-control @error('items.' . $index . '.jumlah') is-invalid @enderror">
                            @error('items.' . $index . '.jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
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
